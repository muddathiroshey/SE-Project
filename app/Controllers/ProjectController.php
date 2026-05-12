<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;
use App\Models\Data;
use App\Models\Notification;
use App\Models\Project;

class ProjectController extends Controller
{
    protected Data         $conn;
    protected Notification $notif;

    public function __construct()
    {
        parent::__construct();
        $this->conn  = new Data();
        $this->notif = new Notification();
    }

    // ── GET /post-job ─────────────────────────────────
    public function postJob(): void
    {
        $this->requireRole('Client');
        $this->view('job/post-job');
    }

    // ── POST /post-job ────────────────────────────────
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/post-job');
        }

        $this->requireRole('Client');

        $errors = $this->validateProjectPost($_POST, $_FILES);
        if ($errors) {
            $this->view('job/post-job', ['errors' => $errors, 'old' => $_POST]);
            return;
        }

        $clientModel = new Client();
        $client      = $clientModel->getByUserId((int) $_SESSION['user_id']);
        if (!$client) {
            $this->redirect('/profile/setup');
        }

        $project                          = new Project();
        $project->user_id                 = (int) $_SESSION['user_id'];
        $project->client_id               = (int) $client['id'];
        $project->niche                   = trim($_POST['niche']                      ?? '');
        $project->niche_answers_json      = $this->jsonPayload($_POST['niche_answers_json'] ?? '', $_POST['niche_answers'] ?? []);
        $project->project_title           = trim($_POST['project_title']              ?? '');
        $project->project_brief           = trim($_POST['project_brief']              ?? '');
        $project->project_full_requirements = trim($_POST['project_full_requirements'] ?? '');
        $project->ideal_candidate         = trim($_POST['ideal_candidate']            ?? '');
        $project->milestones_json         = $this->milestonesPayload($_POST['milestones'] ?? [], $_POST['milestones_json'] ?? '');
        $project->total_budget            = $this->milestoneTotal($_POST['milestones'] ?? []);
        $project->platform_fee            = round($project->total_budget * 0.065, 2);
        $project->specialist_receives     = round($project->total_budget - $project->platform_fee, 2);
        $project->first_escrow_required   = $this->firstMilestoneAmount($_POST['milestones'] ?? []);
        $project->free_revisions          = isset($_POST['free_revisions']) ? 1 : 0;
        $project->nda_type                = $_POST['nda_type']    ?? 'standard';
        $project->nda_duration            = trim($_POST['nda_duration']   ?? '');
        $project->nda_damages             = trim($_POST['nda_damages']    ?? '');
        $project->nda_custom_amount       = isset($_POST['nda_custom_amount']) ? (int) $_POST['nda_custom_amount'] : 0;
        $project->nda_file_path           = null;
        $project->profile_masking         = isset($_POST['profile_masking']) ? intval($_POST['profile_masking']) : 0;
        $project->visibility              = $_POST['visibility'] ?? 'public';

        if ($project->nda_type === 'custom' && !empty($_FILES['nda_file'])) {
            $uploadBase = __DIR__ . '/../../public/uploads/ndas/';
            if (!is_dir($uploadBase)) {
                mkdir($uploadBase, 0755, true);
            }
            $tmp  = $_FILES['nda_file']['tmp_name'] ?? null;
            $name = $_FILES['nda_file']['name']     ?? '';
            if ($tmp && is_uploaded_file($tmp)) {
                $safe     = preg_replace('/[^A-Za-z0-9._-]/', '_', $name);
                $filename = time() . '_' . $safe;
                move_uploaded_file($tmp, $uploadBase . $filename);
                $project->nda_file_path = 'uploads/ndas/' . $filename;
            }
        }

        $projectId = $project->save($project);
        if (!$projectId) {
            die('Failed to save project.');
        }

        $this->redirect('/dashboard');
    }

    // ── GET /job-view ─────────────────────────────────
    public function Jobview(): void
    {
        $this->requireAuth();
        $this->view('job/job-view');
    }

    // ── GET /project-detail?id={n} ────────────────────
    // Works for BOTH specialist view (project-detail-specialist.php)
    // and client view (project-detail.php), chosen by role.
    public function ProjectDetail(): void
    {
        $this->requireAuth();

        $user_id    = (int) $_SESSION['user_id'];
        $project_id = (int) ($_GET['id'] ?? 0);
        $role       = $_SESSION['role'] ?? '';

        if (!$project_id) {
            $this->redirect('/dashboard');
        }

        $_SESSION['notif_unread'] = $this->notif->countUnread($user_id);

        $data = $this->buildProjectDetail($project_id, $user_id, $role);

        if (!$data) {
            $this->redirect('/dashboard');
        }

        // Choose view by role
        $view = ($role === 'Freelancer')
            ? 'job/project-detail'
            : 'job/project-detail';

        $this->view($view, $data);
    }

    // ── GET /project-detail-dispute?id={n} ───────────
    public function ProjectDetailInDispute(): void
    {
        $this->requireAuth();

        $user_id    = (int) $_SESSION['user_id'];
        $project_id = (int) ($_GET['id'] ?? 0);
        $role       = $_SESSION['role'] ?? '';

        if (!$project_id) {
            $this->redirect('/dashboard');
        }

        $_SESSION['notif_unread'] = $this->notif->countUnread($user_id);

        $data = $this->buildProjectDetail($project_id, $user_id, $role);
        if (!$data) {
            $this->redirect('/dashboard');
        }

        // Inject active dispute
        $data['active_dispute'] = $this->getActiveDispute($project_id);

        $this->view('job/project-detail(in-dispute)', $data);
    }

    // ─────────────────────────────────────────────────
    //  Core builder — used by both detail routes
    // ─────────────────────────────────────────────────
    private function buildProjectDetail(int $project_id, int $user_id, string $role): ?array
    {
        $db = $this->conn->getDb();

        // ── 1. Project row ────────────────────────────
        $stmt = $db->prepare(
            "SELECT p.*,
                    COALESCE(cp.org_name, uc.user_name) AS client_display_name,
                    cp.id          AS client_profile_id,
                    uc.id          AS client_user_id,
                    uc.user_name   AS client_user_name,
                    uc.user_email  AS client_email,
                    uc.is_verified AS client_verified,
                    cp.reputation_score    AS client_rating,
                    cp.projects_completed  AS client_projects,
                    cp.org_name, cp.org_type, cp.country AS client_country,
                    cp.timezone AS client_timezone,
                    sp.id          AS specialist_profile_id,
                    sp.user_id     AS specialist_user_id,
                    sp.primary_niche,
                    sp.rating_avg  AS specialist_rating,
                    sp.project_number AS specialist_projects,
                    usp.user_name  AS specialist_name,
                    usp.user_email AS specialist_email,
                    usp.is_verified AS specialist_verified,
                    b.id           AS accepted_bid_id,
                    b.free_reviews AS free_revisions,
                    b.review_price AS revision_price
             FROM projects p
             JOIN clientProfile cp    ON cp.id  = p.client_id
             JOIN userData uc         ON uc.id  = cp.user_id
             JOIN specialistProfiles sp ON sp.id = p.specialist_id
             JOIN userData usp        ON usp.id = sp.user_id
             LEFT JOIN bids b         ON b.id   = p.bid_id
             WHERE p.project_id = ?"
        );
        $stmt->bind_param('i', $project_id);
        $stmt->execute();
        $project = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$project) {
            $db->close();
            return null;
        }

        // Access check — must be one of the parties
        $isClient     = ($project['client_user_id']     == $user_id);
        $isSpecialist = ($project['specialist_user_id']  == $user_id);
        $isAdmin      = in_array($role, ['Admin', 'Arbitrator']);

        if (!$isClient && !$isSpecialist && !$isAdmin) {
            $db->close();
            return null;
        }

        // Contract ref
        $project['contract_ref'] = 'CON-NX-' . str_pad((string) $project_id, 4, '0', STR_PAD_LEFT);

        // ── 2. All milestones ─────────────────────────
        $stmt = $db->prepare(
            "SELECT * FROM project_milestones
             WHERE project_id = ?
             ORDER BY sort_order ASC"
        );
        $stmt->bind_param('i', $project_id);
        $stmt->execute();
        $milestones = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Attach deliverables to each milestone
        foreach ($milestones as &$ms) {
            $dstmt = $db->prepare(
                "SELECT * FROM bid_milestones WHERE bid_id = ? AND sort_order = ? LIMIT 1"
            );
            $dstmt->bind_param('ii', $project['accepted_bid_id'], $ms['sort_order']);
            $dstmt->execute();
            $ms['deliverables_spec'] = $dstmt->get_result()->fetch_assoc() ?: [];
            $dstmt->close();
        }
        unset($ms);

        // Identify active milestone
        $active_milestone = null;
        $done_milestones  = [];
        $locked_milestones = [];
        foreach ($milestones as $ms) {
            if (in_array($ms['status'], ['in_progress', 'submitted', 'revision_requested'])) {
                $active_milestone = $ms;
            } elseif (in_array($ms['status'], ['paid', 'approved'])) {
                $done_milestones[] = $ms;
            } else {
                $locked_milestones[] = $ms;
            }
        }

        // Progress % of active milestone (deliverables submitted vs total)
        if ($active_milestone) {
            $total_del    = 4; // default — refine when deliverable table is added
            $done_del     = 0;
            $active_milestone['progress_pct']     = $total_del > 0 ? round(($done_del / $total_del) * 100) : 0;
            $active_milestone['days_left']        = $active_milestone['due_date']
                ? max(0, (int) ceil((strtotime($active_milestone['due_date']) - time()) / 86400))
                : null;
        }

        // ── 3. WIP snapshots ──────────────────────────
        $wip_snapshots = [];
        if ($active_milestone) {
            $stmt = $db->prepare(
                "SELECT ba.*, 'wip' AS type
                 FROM bid_attachments ba
                 JOIN bids b ON b.id = ba.bid_id
                 WHERE b.id = ?
                 ORDER BY ba.uploaded_at DESC
                 LIMIT 10"
            );
            $stmt->bind_param('i', (int) $project['accepted_bid_id']);
            $stmt->execute();
            $wip_snapshots = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

        // ── 4. Funds summary ──────────────────────────
        $cleared  = 0.0;
        $escrowed = 0.0;
        $on_hold  = 0.0;
        $pending  = 0.0;

        $stmt = $db->prepare(
            "SELECT status, SUM(amount) AS total FROM escrow
             WHERE project_id = ? GROUP BY status"
        );
        $stmt->bind_param('i', $project_id);
        $stmt->execute();
        $escrow_rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($escrow_rows as $er) {
            match($er['status']) {
                'released'  => $cleared  += (float)$er['total'],
                'held'      => $escrowed += (float)$er['total'],
                'disputed'  => $on_hold  += (float)$er['total'],
                default     => $pending  += (float)$er['total'],
            };
        }

        $fee_rate = 0.065;
        $funds = [
            'cleared'          => $cleared,
            'escrowed'         => $escrowed,
            'on_hold'          => $on_hold,
            'pending'          => $pending,
            'total'            => (float) $project['total_amount'],
            'remaining_locked' => (float) $project['total_amount'] - $cleared - $escrowed - $on_hold,
            'fee_rate'         => $fee_rate,
        ];

        // Net per milestone for earnings forecast
        foreach ($milestones as &$ms) {
            $ms['net_amount'] = round((float)$ms['amount'] * (1 - $fee_rate), 2);
        }
        unset($ms);

        // ── 5. Unread messages ────────────────────────
        $stmt = $db->prepare(
            "SELECT COUNT(*) AS cnt FROM messages
             WHERE project_id = ? AND receiver_id = ? AND is_read = 0"
        );
        $stmt->bind_param('ii', $project_id, $user_id);
        $stmt->execute();
        $messages_unread = (int) $stmt->get_result()->fetch_assoc()['cnt'];
        $stmt->close();

        // ── 6. Revisions used for active milestone ────
        $revisions_used = 0;
        if ($active_milestone) {
            $stmt = $db->prepare(
                "SELECT COUNT(*) AS cnt FROM messages
                 WHERE project_id = ?
                   AND body LIKE '%revision%'
                   AND sender_id = ?
                   AND created_at >= ?"
            );
            $since = $active_milestone['created_at'] ?? date('Y-m-d');
            $client_uid = (int) $project['client_user_id'];
            $stmt->bind_param('iis', $project_id, $client_uid, $since);
            $stmt->execute();
            $revisions_used = (int) $stmt->get_result()->fetch_assoc()['cnt'];
            $stmt->close();
        }

        // ── 7. Amendments (scope changes from messages) ─
        $amendments = [];

        $db->close();

        return [
            'project'          => $project,
            'milestones'       => $milestones,
            'active_milestone' => $active_milestone,
            'done_milestones'  => $done_milestones,
            'locked_milestones'=> $locked_milestones,
            'wip_snapshots'    => $wip_snapshots,
            'funds'            => $funds,
            'messages_unread'  => $messages_unread,
            'amendments'       => $amendments,
            'revisions_used'   => $revisions_used,
            'free_revisions'   => (int) ($project['free_revisions'] ?? 2),
            'revision_price'   => (float) ($project['revision_price'] ?? 140),
            'is_client'        => $isClient,
            'is_specialist'    => $isSpecialist,
            'specialist'       => ['user_name' => $_SESSION['user_name'] ?? 'User'],
            'user_id'          => $user_id,
            'role'             => $role,
        ];
    }

    // ── Helper: active dispute for a project ─────────
    private function getActiveDispute(int $project_id): ?array
    {
        $db   = $this->conn->getDb();
        $stmt = $db->prepare(
            "SELECT d.*, u_r.user_name AS raised_by_name, u_a.user_name AS against_name
             FROM disputes d
             JOIN userData u_r ON u_r.id = d.raised_by
             JOIN userData u_a ON u_a.id = d.against
             WHERE d.project_id = ? AND d.status IN ('open','under_review')
             ORDER BY d.created_at DESC LIMIT 1"
        );
        $stmt->bind_param('i', $project_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        $db->close();
        return $row;
    }

    // ─────────────────────────────────────────────────
    //  Original private helpers (unchanged)
    // ─────────────────────────────────────────────────

    private function validateProjectPost(array $post, array $files): array
    {
        $errors = [];

        foreach ([
            'niche'                      => 'Project niche',
            'project_title'              => 'Project title',
            'project_brief'              => 'Project brief',
            'project_full_requirements'  => 'Full requirements',
            'ideal_candidate'            => 'Ideal candidate',
        ] as $field => $label) {
            if (trim($post[$field] ?? '') === '') {
                $errors[] = "{$label} is required.";
            }
        }

        if ($this->milestoneTotal($post['milestones'] ?? []) <= 0) {
            $errors[] = 'At least one milestone with a positive amount is required.';
        }

        if (($post['nda_type'] ?? 'standard') === 'custom') {
            $upload = $files['nda_file'] ?? null;
            if (!$upload || ($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $errors[] = 'Upload your custom NDA file.';
            }
        }

        if (!isset($post['agree_terms'])) {
            $errors[] = 'You must agree to the posting terms.';
        }

        return $errors;
    }

    private function jsonPayload(string $json, array $fallback): string
    {
        if ($json !== '' && json_decode($json, true) !== null) {
            return $json;
        }
        return json_encode($fallback, JSON_UNESCAPED_UNICODE) ?: '[]';
    }

    private function milestonesPayload(array $milestones, string $json): string
    {
        $clean = [];
        foreach ($milestones as $index => $milestone) {
            $amount = (float) ($milestone['amount'] ?? 0);
            $name   = trim($milestone['name'] ?? '');
            if ($name === '' && $amount <= 0) continue;
            $clean[] = [
                'index'         => $index,
                'name'          => $name !== '' ? $name : 'Milestone',
                'duration_days' => (int) ($milestone['duration_days'] ?? $milestone['duration'] ?? 0),
                'amount'        => $amount,
            ];
        }
        if ($clean) {
            return json_encode($clean, JSON_UNESCAPED_UNICODE) ?: '[]';
        }
        return $this->jsonPayload($json, []);
    }

    private function milestoneTotal(array $milestones): float
    {
        return array_reduce(
            $milestones,
            fn($total, $ms) => $total + (float) ($ms['amount'] ?? 0),
            0.0
        );
    }

    private function firstMilestoneAmount(array $milestones): float
    {
        $first = reset($milestones);
        return is_array($first) ? (float) ($first['amount'] ?? 0) : 0.0;
    }
}