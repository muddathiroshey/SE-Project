<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Admin;

class AdminController extends Controller
{
    private Admin $admin;

    public function __construct()
    {
        parent::__construct();
        $this->admin = new Admin();
    }

    // ── Guard helper ─────────────────────────────────────────
    private function requireAdmin(): void
    {
        $this->requireAuth();
        if (($_SESSION['role'] ?? '') !== 'Admin') {
            $this->redirect('/dashboard');
        }
    }

    public function dashboard(): void
    {
        $this->requireAdmin();

        $stats    = $this->admin->getDashboardStats();
        $alerts   = $this->admin->getSystemAlerts();
        $niches   = $this->admin->getNichePerformance();
        $sanctions = $this->admin->getSanctions();

        // Pull the 3 most recent active sanctions for the sidebar widget
        $recentSanctions = array_slice(
            array_merge(
                $sanctions['warnings'],
                $sanctions['limited_bans'],
                $sanctions['permanent_bans']
            ),
            0, 3
        );

        $this->view('admin/admin-dashboard', [
            'stats'           => $stats,
            'alerts'          => $alerts,
            'niches'          => $niches,
            'recentSanctions' => $recentSanctions,
        ]);
    }

    public function kycQueue(): void
    {
        $this->requireAdmin();

        $filter  = $_GET['filter'] ?? 'all';
        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $stats   = $this->admin->getDashboardStats();
        $kyc     = $this->admin->getKycQueue($filter, $page);

        $this->view('admin/admin-kyc', [
            'stats'  => $stats,
            'kyc'    => $kyc,
            'filter' => $filter,
        ]);
    }

    public function kycDetail(): void
    {
        $this->requireAdmin();

        $id         = (int) ($_GET['id'] ?? 0);
        $submission = $id ? $this->admin->getKycDetail($id) : null;

        if (!$submission) {
            $this->redirect('/admin/kyc');
        }

        $this->view('admin/admin-kyc-detail', ['submission' => $submission]);
    }

    public function kycDecide(): void
    {
        $this->requireAdmin();

        $id       = (int) ($_POST['submission_id'] ?? 0);
        $decision = $_POST['decision'] ?? '';   // 'approved' | 'rejected'
        $notes    = trim($_POST['notes'] ?? '');
        $adminId  = (int) $_SESSION['user_id'];

        if (!$id || !in_array($decision, ['approved', 'rejected'], true)) {
            $this->redirect('/admin/kyc');
        }

        $this->admin->updateKycDecision($id, $decision, $notes, $adminId);
        $this->redirect('/admin/kyc?decided=1');
    }

    public function sanctions(): void
    {
        $this->requireAdmin();

        $sanctions = $this->admin->getSanctions();
        $stats     = $this->admin->getDashboardStats();

        $this->view('admin/sanctions', [
            'sanctions' => $sanctions,
            'stats'     => $stats,
        ]);
    }

    public function sanctionCreate(): void
    {
        $this->requireAdmin();

        $userId       = (int) ($_POST['user_id'] ?? 0);
        $tier         = $_POST['tier'] ?? '';
        $reason       = trim($_POST['reason'] ?? '');
        $message      = trim($_POST['message'] ?? '');
        $durationDays = isset($_POST['duration_days']) && $_POST['duration_days'] !== ''
            ? (int) $_POST['duration_days']
            : null;
        $adminId = (int) $_SESSION['user_id'];

        if (!$userId || !in_array($tier, ['warning', 'limited_ban', 'permanent_ban'], true)) {
            $this->redirect('/admin/sanctions');
        }

        $this->admin->createSanction($userId, $tier, $reason, $message, $durationDays, $adminId);
        $this->redirect('/admin/sanctions?created=1');
    }

    public function sanctionWithdraw(): void
    {
        $this->requireAdmin();

        $sanctionId = (int) ($_POST['sanction_id'] ?? 0);
        $message    = trim($_POST['message'] ?? '');
        $adminId    = (int) $_SESSION['user_id'];

        if ($sanctionId) {
            $this->admin->withdrawSanction($sanctionId, $message, $adminId);
        }

        $this->redirect('/admin/sanctions?withdrawn=1');
    }

    public function disputes(): void
    {
        $this->requireAdmin();

        $disputes = $this->admin->getActiveDisputes();
        $stats    = $this->admin->getDashboardStats();

        $this->view('admin/admin-dispute', [
            'disputes' => $disputes,
            'stats'    => $stats,
        ]);
    }

    public function userSearch(): void
    {
        $this->requireAdmin();

        $q     = trim($_GET['q'] ?? '');
        $users = $q ? $this->admin->searchUsers($q) : [];

        header('Content-Type: application/json');
        echo json_encode($users);
        exit;
    }
}
