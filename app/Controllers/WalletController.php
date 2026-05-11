<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;
use App\Models\Specialist;
use App\Models\Wallet;

class WalletController extends Controller
{
    private Wallet $wallet;

    public function __construct()
    {
        parent::__construct();
        $this->wallet = new Wallet();
    }

    // ── GET /wallet  (role-aware) ─────────────────────────────
    public function index(): void
    {
        $this->requireAuth();

        $user_id = (int) $_SESSION['user_id'];
        $role    = $_SESSION['role'] ?? '';

        if ($role === 'Freelancer') {
            $this->specialistWallet($user_id);
        } else {
            $this->requireRole('Client');
            $this->clientWallet($user_id);
        }
    }

    // ── GET /wallet/transactions (AJAX / full page) ───────────
    public function transactions(): void
    {
        $this->requireAuth();
        $user_id = (int) $_SESSION['user_id'];
        $txns    = $this->wallet->getTransactions($user_id);

        // JSON response if requested via AJAX
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode($txns);
            exit();
        }

        $this->view('wallet/transactions', ['transactions' => $txns]);
    }

    // ── POST /wallet/fund  (Client funds a milestone) ─────────
    public function fund(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/wallet');
        }

        $this->requireRole('Client');

        $amount     = (float) ($_POST['amount']     ?? 0);
        $project_id = (int)   ($_POST['project_id'] ?? 0);
        $user_id    = (int)   $_SESSION['user_id'];

        if ($amount <= 0 || !$project_id) {
            $_SESSION['wallet_error'] = 'Invalid amount or project.';
            $this->redirect('/wallet');
        }

        $ok = $this->wallet->addTransaction(
            $user_id,
            'escrow_hold',
            $amount,
            $project_id,
            'Milestone funding — project #' . $project_id
        );

        $_SESSION[$ok ? 'wallet_success' : 'wallet_error'] = $ok
            ? 'Milestone funded successfully.'
            : 'Funding failed. Please try again.';

        $this->redirect('/wallet');
    }

    // ── private helpers ───────────────────────────────────────

    private function clientWallet(int $user_id): void
    {
        $clientModel = new Client();
        $client      = $clientModel->getByUserId($user_id);

        if (!$client) {
            $this->redirect('/profile/setup');
        }

        $summary  = $this->wallet->getClientSummary($user_id);
        $escrow   = $this->wallet->getActiveEscrow($user_id);
        $txns     = $this->wallet->getTransactions($user_id);
        $walletRow = $this->wallet->getOrCreate($user_id);

        $this->view('/dashboard/client/client-wallet', [
            'client'   => $client,
            'summary'  => $summary,
            'escrow'   => $escrow,
            'transactions' => $txns,
            'wallet'   => $walletRow,
            'error'    => $_SESSION['wallet_error']   ?? null,
            'success'  => $_SESSION['wallet_success'] ?? null,
        ]);

        unset($_SESSION['wallet_error'], $_SESSION['wallet_success']);
    }

    private function specialistWallet(int $user_id): void
    {
        $specialistModel = new Specialist();
        $specialist      = $specialistModel->getByUserId($user_id);

        if (!$specialist) {
            $this->redirect('/profile/setup');
        }

        $summary  = $this->wallet->getSpecialistSummary($user_id);
        $txns     = $this->wallet->getTransactions($user_id);
        $walletRow = $this->wallet->getOrCreate($user_id);

        $this->view('dashboard/specialist/specialist-wallet', [
            'specialist'   => $specialist,
            'summary'      => $summary,
            'transactions' => $txns,
            'wallet'       => $walletRow,
            'error'        => $_SESSION['wallet_error']   ?? null,
            'success'      => $_SESSION['wallet_success'] ?? null,
        ]);

        unset($_SESSION['wallet_error'], $_SESSION['wallet_success']);
    }
}
