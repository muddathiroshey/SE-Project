<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;
use App\Models\Job;
use App\Models\Project;
use App\Models\Specialist;
use App\Models\Data;

class JobController extends Controller
{
    private Job $job;

    public function __construct()
    {
        parent::__construct();
        $this->job = new Job();
    }

    // ─────────────────────────────────────────────────────────────
    // GET /browse-jobs  (Specialist)
    // ─────────────────────────────────────────────────────────────
    public function browseJobs(): void
    {
        $this->requireRole('Freelancer');

        $filters = [
            'niche'      => trim($_GET['niche']      ?? ''),
            'q'          => trim($_GET['q']           ?? ''),
            'budget_min' => $_GET['budget_min']       ?? '',
            'budget_max' => $_GET['budget_max']       ?? '',
        ];
        $page   = max(1, (int) ($_GET['page'] ?? 1));
        $result = $this->job->browse($filters, $page);
        $niches = $this->job->getNichesWithCounts();

        $specialistModel = new Specialist();
        $specialist      = $specialistModel->getByUserId((int) $_SESSION['user_id']);

        $this->view('job/browse-jobs', [
            'jobs'       => $result['data'],
            'total'      => $result['total'],
            'pages'      => $result['pages'],
            'page'       => $page,
            'filters'    => $filters,
            'niches'     => $niches,
            'specialist' => $specialist,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // GET /browse-experts  (Client)
    // ─────────────────────────────────────────────────────────────
    public function browseExperts(): void
    {
        $this->requireRole('Client');

        $filters = [
            'niche'         => trim($_GET['niche']        ?? ''),
            'q'             => trim($_GET['q']             ?? ''),
            'verified_only' => (bool) ($_GET['verified']  ?? false),
        ];
        $page   = max(1, (int) ($_GET['page'] ?? 1));
        $result = $this->job->browseExperts($filters, $page);
        $niches = $this->job->getNichesWithCounts();

        $clientModel = new Client();
        $client      = $clientModel->getByUserId((int) $_SESSION['user_id']);

        $this->view('job/browse-experts', [
            'experts' => $result['data'],
            'total'   => $result['total'],
            'pages'   => $result['pages'],
            'page'    => $page,
            'filters' => $filters,
            'niches'  => $niches,
            'client'  => $client,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // GET /incoming-bids  (Client)
    // ─────────────────────────────────────────────────────────────
    public function incomingBids(): void
    {
        $this->requireRole('Client');

        $clientModel = new Client();
        $client      = $clientModel->getByUserId((int) $_SESSION['user_id']);
        if (!$client) {
            $this->redirect('/profile/setup');
        }

        $filters = [
            'status' => trim($_GET['status'] ?? ''),
            'job_id' => (int) ($_GET['project'] ?? 0) ?: null,
        ];

        $bids     = $this->job->getIncomingBids((int) $client['id'], $filters);
        $projects = $this->job->getByClientId((int) $client['id']);

        $stats = [
            'total_bids'    => count($bids),
            'new_bids'      => count(array_filter($bids,     fn($b) => $b['status'] === 'submitted')),
            'shortlisted'   => count(array_filter($bids,     fn($b) => $b['status'] === 'shortlisted')),
            'projects_open' => count(array_filter($projects, fn($p) => $p['status'] === 'posted')),
        ];

        $this->view('job/incoming-bids', [
            'bids'           => $bids,
            'projects'       => $projects,
            'stats'          => $stats,
            'active_filters' => $filters,
            'client'         => $client,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // GET /my-bids  (Specialist)
    // ─────────────────────────────────────────────────────────────
    public function myBids(): void
    {
        $this->requireRole('Freelancer');

        $userId  = (int) $_SESSION['user_id'];
        $status  = trim($_GET['status'] ?? '');

        $proposals = $this->job->getMyBids($userId, $status);
        $stats     = $this->job->getBidStats($userId);

        $specialistModel = new Specialist();
        $specialist      = $specialistModel->getByUserId($userId);

        $this->view('job/my-bids', [
            'proposals'  => $proposals,
            'stats'      => $stats,
            'filters'    => ['status' => $status],
            'specialist' => $specialist,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // GET /jobs/{id}  |  /job-view?id={id}  (Specialist)
    //
    // Data contract for Views/job/job-view.php:
    //   $job          — full job record
    //   $client       — posting client + org profile
    //   $milestones   — ordered milestone rows
    //   $ndaRequired  — bool
    //   $bidCount     — int total proposals received
    //   $myBid        — null | this specialist's existing bid
    //   $canBid       — bool
    //   $blockReason  — null | string
    //   $similarJobs  — array(3) of related postings
    //   $specialist   — authenticated user record
    //   $bidJustSubmitted — bool: show success modal immediately
    // ─────────────────────────────────────────────────────────────
    public function show(): void
    {
        $this->requireAuth();

        // Accept both /jobs/{id} (route param) and /job-view?id={id}
        $id  = (int) ($_GET['id'] ?? 0);
        $job = $id > 0 ? $this->job->getById($id) : null;

        if (!$job) {
            $this->redirect('/browse-jobs');
        }

        $jobId = (int) $job['id'];

        // Milestones for this job
        $milestones = $this->getJobMilestones($jobId);

        // Client profile
        $client = $this->getClientProfileForJob($jobId, (int) ($job['client_id'] ?? 0));

        // Bid count (aggregate, never individual)
        $bidCount = $this->getJobBidCount($jobId);

        // Specialist's existing bid (if any)
        $myBid = null;
        if (isset($_SESSION['user_id'])) {
            $myBid = $this->getSpecialistBidForJob($jobId, (int) $_SESSION['user_id']);
        }

        // Can this specialist bid?
        [$canBid, $blockReason] = $this->resolveCanBid($job, $myBid);

        // Similar jobs (same niche, not this one, active)
        $similarJobs = $this->getSimilarJobs($jobId, $job['niche'] ?? '', 3);

        // Specialist profile
        $specialistModel = new Specialist();
        $specialist      = isset($_SESSION['user_id'])
            ? ($specialistModel->getByUserId((int) $_SESSION['user_id']) ?? [])
            : [];

        // NDA required flag
        $ndaRequired = !empty($job['nda_required']);

        // Was this page loaded right after submitting a bid?
        $bidJustSubmitted = !empty($_GET['submitted']);

        $this->view('job/job-view', [
            'job'              => $job,
            'client'           => $client,
            'milestones'       => $milestones,
            'ndaRequired'      => $ndaRequired,
            'bidCount'         => $bidCount,
            'myBid'            => $myBid,
            'canBid'           => $canBid,
            'blockReason'      => $blockReason,
            'similarJobs'      => $similarJobs,
            'specialist'       => $specialist,
            'bidJustSubmitted' => $bidJustSubmitted,
        ]);
    }

    // Alias for backward-compat route /job-view?id=
    public function jobView(): void
    {
        $this->show();
    }

    // ─────────────────────────────────────────────────────────────
    // GET /post-job  (Client)
    //
    // Data contract for Views/job/post-job.php:
    //   $errors  — [] (empty on GET)
    //   $old     — [] (empty on GET)
    //   $client  — authenticated client record
    //   $niches  — array of niche definitions
    //   $feeRate — float platform fee rate
    // ─────────────────────────────────────────────────────────────
    public function postJob(): void
    {
        $this->requireRole('Client');

        $clientModel = new Client();
        $client      = $clientModel->getByUserId((int) $_SESSION['user_id']);
        if (!$client) {
            $this->redirect('/profile/setup');
        }

        $this->view('job/post-job', [
            'errors'  => [],
            'old'     => [],
            'client'  => $client,
            'niches'  => $this->getAvailableNiches(),
            'feeRate' => $this->getPlatformFeeRate(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // POST /post-job  (Client)
    // ─────────────────────────────────────────────────────────────
    public function storeJob(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/post-job');
        }

        $this->requireRole('Client');

        $clientModel = new Client();
        $client      = $clientModel->getByUserId((int) $_SESSION['user_id']);
        if (!$client) {
            $this->redirect('/profile/setup');
        }

        [$errors, $errorStep] = $this->validateJobPost($_POST, $_FILES);

        if (!empty($errors)) {
            $this->view('job/post-job', [
                'errors'  => $errors,
                'old'     => array_merge($_POST, ['_error_step' => $errorStep]),
                'client'  => $client,
                'niches'  => $this->getAvailableNiches(),
                'feeRate' => $this->getPlatformFeeRate(),
            ]);
            return;
        }

        // Build and persist the job
        $jobId = $this->persistJob($_POST, $_FILES, (int) $client['id']);

        if (!$jobId) {
            $this->view('job/post-job', [
                'errors'  => ['Something went wrong saving your project. Please try again.'],
                'old'     => array_merge($_POST, ['_error_step' => 5]),
                'client'  => $client,
                'niches'  => $this->getAvailableNiches(),
                'feeRate' => $this->getPlatformFeeRate(),
            ]);
            return;
        }

        $this->redirect('/jobs/' . $jobId . '?posted=1');
    }

    // ─────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────

    private function getJobMilestones(int $jobId): array
    {
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare(
            'SELECT * FROM project_milestones WHERE project_id = ? ORDER BY sort_order, id'
        );
        if (!$stmt) return [];
        $stmt->bind_param('i', $jobId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    private function getClientProfileForJob(int $jobId, int $fallbackClientId = 0): array
    {
        $db   = new Data();
        $conn = $db->getDb();

        // Try to resolve from the project's client_id
        $sql = "SELECT u.*, cp.org_name, cp.industry, cp.city, cp.location, cp.bio, cp.verified,
                       cp.payment_reliability, cp.dispute_rate, cp.completed_projects,
                       cp.repeat_hire_rate, cp.avg_approval_hours, cp.slug
                FROM projects p
                JOIN userData u  ON u.id = p.client_id
                LEFT JOIN client_profiles cp ON cp.user_id = u.id
                WHERE p.id = ?
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            return [];
        }
        $stmt->bind_param('i', $jobId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();

        return $row ?? [];
    }

    private function getJobBidCount(int $jobId): int
    {
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare(
            "SELECT COUNT(*) AS c FROM bids WHERE job_id = ? AND status NOT IN ('withdrawn')"
        );
        if (!$stmt) return 0;
        $stmt->bind_param('i', $jobId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();
        return (int) ($row['c'] ?? 0);
    }

    private function getSpecialistBidForJob(int $jobId, int $userId): ?array
    {
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare(
            'SELECT * FROM bids WHERE job_id = ? AND user_id = ? ORDER BY submitted_at DESC LIMIT 1'
        );
        if (!$stmt) return null;
        $stmt->bind_param('ii', $jobId, $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $row ?: null;
    }

    /**
     * Determine whether the current authenticated specialist can bid.
     * Returns [bool $canBid, ?string $blockReason].
     */
    private function resolveCanBid(array $job, ?array $myBid): array
    {
        // Already has an active bid
        if ($myBid && !in_array($myBid['status'] ?? '', ['withdrawn', 'rejected'], true)) {
            return [false, 'You have already submitted a proposal for this project.'];
        }

        // Job not open
        if (($job['status'] ?? '') !== 'posted') {
            return [false, 'This project is no longer accepting proposals.'];
        }

        // Invitation-only and this specialist is not invited
        if (($job['visibility'] ?? 'public') === 'invitation-only') {
            $userId = (int) ($_SESSION['user_id'] ?? 0);
            if (!$this->isSpecialistInvited((int) $job['id'], $userId)) {
                return [false, 'This project is invitation-only. You have not been invited to bid.'];
            }
        }

        // Specialist not yet verified
        $specialistModel = new Specialist();
        $specialist      = $specialistModel->getByUserId((int) ($_SESSION['user_id'] ?? 0));
        if (empty($specialist['verified'])) {
            return [false, 'Your profile must be verified before you can submit proposals.'];
        }

        return [true, null];
    }

    private function isSpecialistInvited(int $jobId, int $userId): bool
    {
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare(
            'SELECT id FROM job_invitations WHERE job_id = ? AND user_id = ? LIMIT 1'
        );
        if (!$stmt) return false;
        $stmt->bind_param('ii', $jobId, $userId);
        $stmt->execute();
        $found = (bool) $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $found;
    }

    private function getSimilarJobs(int $excludeJobId, string $niche, int $limit = 3): array
    {
        if (!$niche) return [];
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare(
            "SELECT id, title, total_budget, niche
             FROM projects
             WHERE niche = ? AND id != ? AND status = 'posted'
             ORDER BY created_at DESC
             LIMIT ?"
        );
        if (!$stmt) return [];
        $stmt->bind_param('sii', $niche, $excludeJobId, $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    private function getAvailableNiches(): array
    {
        // Could be driven from a DB table; hardcoded here for stability
        return [
            ['key' => 'data-science',  'icon' => '🧠', 'name' => 'Data Science & ML'],
            ['key' => 'legal',         'icon' => '⚖️', 'name' => 'Legal Consulting'],
            ['key' => 'translation',   'icon' => '🌐', 'name' => 'Technical Translation'],
            ['key' => 'finance',       'icon' => '📈', 'name' => 'Financial Modelling'],
            ['key' => 'biomedical',    'icon' => '🔬', 'name' => 'Biomedical Research'],
            ['key' => 'cybersecurity', 'icon' => '🔐', 'name' => 'Cybersecurity Audit'],
        ];
    }

    private function getPlatformFeeRate(): float
    {
        // Could be fetched from config/DB; 6.5% default
        return (float) ($_ENV['PLATFORM_FEE_RATE'] ?? defined('PLATFORM_FEE_RATE') ? PLATFORM_FEE_RATE : 0.065);
    }

    /**
     * Validate a project post submission.
     * Returns [$errors, $errorStep].
     */
    private function validateJobPost(array $post, array $files): array
    {
        $errors    = [];
        $errorStep = 1;

        // Step 1 — niche
        $allowedNiches = array_column($this->getAvailableNiches(), 'key');
        if (empty($post['niche']) || !in_array($post['niche'], $allowedNiches, true)) {
            $errors[] = 'Please select a valid project niche.';
            $errorStep = 1;
        }

        // Step 2 — project details
        if (empty($errorStep) || $errorStep === 1) {
            if (strlen(trim($post['project_title'] ?? '')) < 5) {
                $errors['project_title'] = 'Project title must be at least 5 characters.';
                $errorStep = 2;
            }
            if (strlen(trim($post['project_brief'] ?? '')) < 50) {
                $errors['project_brief'] = 'Project brief must be at least 50 characters.';
                $errorStep = max($errorStep, 2);
            }
        }

        // Step 3 — milestones
        $milestones   = $post['milestones'] ?? [];
        $msTotal      = 0;
        $validMs      = false;
        foreach ($milestones as $ms) {
            $amt = (float) ($ms['amount'] ?? 0);
            if ($amt > 0) {
                $validMs  = true;
                $msTotal += $amt;
            }
        }
        if (!$validMs) {
            $errors[] = 'Please add at least one milestone with a payment amount.';
            $errorStep = max($errorStep, 3);
        }

        // Step 4 — NDA
        if (($post['nda_type'] ?? 'standard') === 'standard') {
            if (empty($post['nda_duration'])) {
                $errors[] = 'Please select an NDA duration.';
                $errorStep = max($errorStep, 4);
            }
        } elseif (($post['nda_type'] ?? '') === 'custom') {
            if (empty($files['nda_file']['name'])) {
                $errors[] = 'Please upload your custom NDA document.';
                $errorStep = max($errorStep, 4);
            }
        }

        // Step 5 — terms
        if (empty($post['agree_terms'])) {
            $errors[] = 'You must agree to the Posting Guidelines & Terms.';
            $errorStep = max($errorStep, 5);
        }

        return [$errors, $errorStep];
    }

    /**
     * Persist a valid job post and return the new job ID.
     */
    private function persistJob(array $post, array $files, int $clientId): int
    {
        $db   = new Data();
        $conn = $db->getDb();

        // Resolve niche answers
        $nicheAnswers = [];
        if (!empty($post['niche_answers_json'])) {
            $nicheAnswers = json_decode($post['niche_answers_json'], true) ?: [];
        }

        // Compute totals
        $milestones = $post['milestones'] ?? [];
        if (empty($milestones) && !empty($post['milestones_json'])) {
            $milestones = json_decode($post['milestones_json'], true) ?: [];
        }
        $totalBudget = array_sum(array_column($milestones, 'amount'));
        $totalDays   = array_sum(array_column($milestones, 'duration_days'));

        // Insert project
        $stmt = $conn->prepare(
            "INSERT INTO projects
             (client_id, title, niche, project_brief, project_full_requirements, ideal_candidate,
              total_budget, total_duration_days, first_escrow, nda_required, nda_type, nda_duration,
              nda_damages, nda_custom_amount, visibility, profile_masking, free_revisions,
              niche_answers_json, status, created_at)
             VALUES (?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?,?, ?, 'posted', NOW())"
        );
        if (!$stmt) return 0;

        $title          = trim($post['project_title'] ?? '');
        $niche          = $post['niche'] ?? '';
        $brief          = trim($post['project_brief'] ?? '');
        $reqs           = trim($post['project_full_requirements'] ?? '');
        $ideal          = trim($post['ideal_candidate'] ?? '');
        $ndaType        = $post['nda_type'] ?? 'standard';
        $ndaDuration    = $post['nda_duration'] ?? '2 years';
        $ndaDamages     = $post['nda_damages'] ?? '10000';
        $ndaCustomAmt   = $ndaDamages === 'custom' ? (float) ($post['nda_custom_amount'] ?? 0) : 0;
        $visibility     = ($post['nda_visibility'] ?? 'public') === 'invitation-only' ? 'invitation-only' : 'public';
        $profileMasking = empty($post['profile_masking']) ? 0 : 1;
        $freeRevisions  = empty($post['free_revisions'])  ? 0 : 1;
        $ndaRequired    = 1; // always for now
        $firstEscrow    = (float) ($milestones[0]['amount'] ?? 0);
        $nicheJson      = json_encode($nicheAnswers, JSON_UNESCAPED_UNICODE);

        $stmt->bind_param(
            'isssss ddiisssssiisi s',
            $clientId, $title, $niche, $brief, $reqs, $ideal,
            $totalBudget, $totalDays, $firstEscrow, $ndaRequired, $ndaType, $ndaDuration,
            $ndaDamages, $ndaCustomAmt, $visibility, $profileMasking, $freeRevisions,
            $nicheJson
        );
        $stmt->execute();
        $jobId = (int) $conn->insert_id;
        $stmt->close();

        if (!$jobId) {
            $conn->close();
            return 0;
        }

        // Insert milestones
        $msStmt = $conn->prepare(
            'INSERT INTO project_milestones (project_id, name, duration_days, amount, sort_order)
             VALUES (?, ?, ?, ?, ?)'
        );
        if ($msStmt) {
            foreach ($milestones as $i => $ms) {
                $msName = trim($ms['name'] ?? '') ?: 'Milestone ' . ($i + 1);
                $msDays = (int) ($ms['duration_days'] ?? 0);
                $msAmt  = (float) ($ms['amount'] ?? 0);
                if ($msAmt <= 0) continue;
                $sortOrder = $i;
                $msStmt->bind_param('isidi', $jobId, $msName, $msDays, $msAmt, $sortOrder);
                $msStmt->execute();
            }
            $msStmt->close();
        }

        // Upload custom NDA if provided
        if (($post['nda_type'] ?? '') === 'custom' && !empty($files['nda_file']['tmp_name'])) {
            $uploadDir = __DIR__ . '/../../public/uploads/ndas/' . $jobId . '/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $safe = preg_replace('/[^A-Za-z0-9._-]/', '_', $files['nda_file']['name']);
            move_uploaded_file($files['nda_file']['tmp_name'], $uploadDir . $safe);

            // Store path in project row
            $upStmt = $conn->prepare('UPDATE projects SET nda_file_path = ? WHERE id = ?');
            if ($upStmt) {
                $path = 'uploads/ndas/' . $jobId . '/' . $safe;
                $upStmt->bind_param('si', $path, $jobId);
                $upStmt->execute();
                $upStmt->close();
            }
        }

        $conn->close();
        return $jobId;
    }
}