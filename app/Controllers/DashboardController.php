<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Data;
use App\Models\Wallet;
use App\Models\Notification;

class DashboardController extends Controller
{
    protected Data         $conn;
    protected Wallet       $wallet;
    protected Notification $notif;

    public function __construct()
    {
        parent::__construct();
        $this->conn   = new Data();
        $this->wallet = new Wallet();
        $this->notif  = new Notification();
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_SESSION['email'] ?? null;
        if (!$email) { header("Location: /login"); exit(); }

        $user = $this->conn->getUserByEmail($email);
        if (!$user)  { header("Location: /login"); exit(); }

        $role        = $user['user_role'];
        $is_verified = (bool) $user['is_verified'];
        $user_id     = isset($user['id']) ? (int) $user['id'] : 0;

        // Cache unread count in session — used by nav bell badge in every view
        $_SESSION['notif_unread'] = $this->notif->countUnread($user_id);

        if ($role === 'Admin') {
            $this->view('admin/admin-dashboard');
            return;
        }

        $active_projects_count = $this->conn->getActiveProjectsCount($user_id, $role);
        $active_projects       = $this->conn->getActiveProjects($user_id, $role);

        // ── FREELANCER ────────────────────────────────────────
        if ($role === 'Freelancer') {
            if (!$is_verified) {
                header("Location: /profile");
                exit();
            }

            if ($active_projects_count > 0) {
                $this->view('dashboard/specialist/specialist-active-projects', [
                    'active_projects_count' => $active_projects_count,
                    'projects'              => $active_projects,
                    'specialist'            => $user,
                ]);
                return;
            }

            // No active projects — show full dashboard with
            // matched jobs, proposals, wallet summary, nearest deadline
            $data = $this->buildSpecialistDashboard($user_id, $user);
            $this->view('dashboard/specialist/dashboard-specialist', $data);
            return;
        }

        // ── CLIENT ────────────────────────────────────────────
        if (!$is_verified) {
            header("Location: /profile");
            return;
        }

        if ($active_projects_count > 0) {
            $this->view('dashboard/client/dashboard-client', [
                'active_projects_count' => $active_projects_count,
                'active_projects'       => $active_projects,
            ]);
            return;
        }

        $this->view('dashboard/client/dashboard-client-empty', [
            'active_projects_count' => $active_projects_count,
            'active_projects'       => $active_projects,
        ]);
    }

    public function bids(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_SESSION['email'] ?? null;
        if (!$email) { header("Location: /login"); exit(); }

        $user = $this->conn->getUserByEmail($email);
        if (!$user)  { header("Location: /login"); exit(); }

        $role            = $user['user_role'];
        $is_verified     = (bool) $user['is_verified'];
        $user_id         = isset($user['id']) ? (int) $user['id'] : 0;
        $active_projects = $this->conn->getActiveProjectsCount($user_id, $role);

        if ($role === 'Freelancer') {
            if (!$is_verified) {
                header("Location: /profile");
                exit();
            }
            $this->view('dashboard/specialist/my-bids');
            return;
        }

        // Client
        if (!$is_verified) {
            header("Location: /profile");
            return;
        }

        if ($active_projects > 0) {
            $this->view('dashboard/client/dashboard-client', [
                'active_projects' => $active_projects,
            ]);
            return;
        }

        $this->view('dashboard/client/dashboard-client-empty', [
            'active_projects' => $active_projects,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  Build every variable the specialist dashboard view needs
    // ─────────────────────────────────────────────────────────
    private function buildSpecialistDashboard(int $user_id, array $user): array
    {
        $db = $this->conn->getDb();

        // 1. Specialist profile
        $stmt = $db->prepare('SELECT * FROM specialistProfiles WHERE user_id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $specialist_profile = $stmt->get_result()->fetch_assoc() ?: [];
        $stmt->close();

        // 2. Wallet summary + row
        $wallet_summary = $this->wallet->getSpecialistSummary($user_id);
        $wallet_row     = $this->wallet->getOrCreate($user_id);

        // 3. Bid stats
        $stmt = $db->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(status IN ('submitted','shortlisted')) AS active,
                SUM(status = 'accepted')  AS accepted,
                SUM(status = 'rejected')  AS rejected,
                SUM(status = 'withdrawn') AS withdrawn
             FROM bids WHERE user_id = ?"
        );
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $bid_stats = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $bid_stats['acceptance_rate'] = ($bid_stats['total'] ?? 0) > 0
            ? round(($bid_stats['accepted'] / $bid_stats['total']) * 100)
            : 0;

        // 4. Active proposals (latest 5 submitted/shortlisted)
        $stmt = $db->prepare(
            "SELECT b.*,
                    pp.project_title      AS job_title,
                    pp.niche              AS job_niche,
                    pp.total_budget       AS job_budget,
                    COALESCE(cp.org_name, uc.user_name) AS client_name,
                    uc.is_verified        AS client_verified,
                    (SELECT COUNT(*) FROM bid_milestones bm
                     WHERE bm.bid_id = b.id) AS milestones_count
             FROM bids b
             JOIN project_postings pp ON pp.id = b.job_id
             JOIN clientProfile cp    ON cp.id = pp.client_id
             JOIN userData uc         ON uc.id = cp.user_id
             WHERE b.user_id = ?
               AND b.status IN ('submitted','shortlisted')
             ORDER BY b.submitted_at DESC
             LIMIT 5"
        );
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $active_proposals = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // 5. Nearest upcoming milestone deadline
        $stmt = $db->prepare(
            "SELECT
                pm.*,
                p.project_id,
                p.title              AS project_title,
                p.niche,
                p.total_amount       AS project_budget,
                COALESCE(cp.org_name, uc.user_name) AS client_name,
                DATEDIFF(pm.due_date, CURDATE()) AS days_left,
                (SELECT COUNT(*) FROM project_milestones
                 WHERE project_id = p.project_id) AS total_milestones,
                (SELECT COUNT(*) FROM project_milestones
                 WHERE project_id = p.project_id
                   AND status IN ('paid','approved')) AS done_milestones
             FROM project_milestones pm
             JOIN projects p            ON p.project_id = pm.project_id
             JOIN specialistProfiles sp  ON sp.id        = p.specialist_id
             JOIN clientProfile cp       ON cp.id        = p.client_id
             JOIN userData uc            ON uc.id        = cp.user_id
             WHERE sp.user_id = ?
               AND pm.status IN ('pending','in_progress')
               AND pm.due_date IS NOT NULL
               AND pm.due_date >= CURDATE()
             ORDER BY pm.due_date ASC
             LIMIT 1"
        );
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $nearest_milestone = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();

        // 6. Matched jobs — same niche, open, not already bid on
        $niche = $specialist_profile['primary_niche'] ?? '';
        $stmt  = $db->prepare(
            "SELECT pp.*,
                    COALESCE(cp.org_name, uc.user_name) AS client_display_name,
                    uc.is_verified       AS client_verified,
                    cp.reputation_score  AS client_rating,
                    (SELECT COUNT(*) FROM bids b2
                     WHERE b2.job_id = pp.id) AS bid_count
             FROM project_postings pp
             JOIN clientProfile cp ON cp.id = pp.client_id
             JOIN userData uc       ON uc.id = pp.user_id
             WHERE pp.niche  = ?
               AND pp.status = 'posted'
               AND pp.id NOT IN (
                   SELECT job_id FROM bids WHERE user_id = ?
               )
             ORDER BY pp.created_at DESC
             LIMIT 8"
        );
        $stmt->bind_param('si', $niche, $user_id);
        $stmt->execute();
        $matched_jobs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($matched_jobs as &$j) {
            $j['milestones'] = json_decode($j['milestones_json'] ?? '[]', true) ?: [];
        }
        unset($j);

        // 7. Unread messages count (sidebar badge)
        $stmt = $db->prepare(
            'SELECT COUNT(*) AS cnt FROM messages WHERE receiver_id = ? AND is_read = 0'
        );
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $unread_messages = (int) $stmt->get_result()->fetch_assoc()['cnt'];
        $stmt->close();

        $db->close();

        return [
            'specialist'            => $user,
            'specialist_profile'    => $specialist_profile,
            'wallet_summary'        => $wallet_summary,
            'wallet'                => $wallet_row,
            'bid_stats'             => $bid_stats,
            'active_proposals'      => $active_proposals,
            'nearest_milestone'     => $nearest_milestone,
            'matched_jobs'          => $matched_jobs,
            'matched_jobs_count'    => count($matched_jobs),
            'active_projects_count' => $this->conn->getActiveProjectsCount($user_id, 'Freelancer'),
            'unread_messages'       => $unread_messages,
        ];
    }
}