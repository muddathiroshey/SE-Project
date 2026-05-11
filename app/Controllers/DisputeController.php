<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Dispute;
use App\Models\Notification;
use App\Models\Data;

class DisputeController extends Controller
{
    private Dispute      $dispute;
    private Notification $notif;

    public function __construct()
    {
        parent::__construct();
        $this->dispute = new Dispute();
        $this->notif   = new Notification();
    }

    // ── GET /dispute  (list user's disputes) ─────────────────
    public function index(): void
    {
        $this->requireAuth();

        $user_id  = (int) $_SESSION['user_id'];
        $disputes = $this->dispute->getForUser($user_id);

        // If a specific dispute is requested, show its detail
        $id = (int) ($_GET['id'] ?? 0);
        if ($id) {
            $this->detail($id, $user_id);
            return;
        }

        $this->view('dispute/dispute', [
            'disputes' => $disputes,
            'user_id'  => $user_id,
        ]);
    }

    // ── GET /dispute?id={n}  (single dispute thread) ─────────
    private function detail(int $id, int $user_id): void
    {
        $dispute = $this->dispute->getById($id);

        if (!$dispute) {
            $this->redirect('/dispute');
        }

        // Access check — only parties + admin/arbitrator
        $role = $_SESSION['role'] ?? '';
        $isParty = ($dispute['raised_by'] == $user_id || $dispute['against'] == $user_id);
        $isAdmin = in_array($role, ['Admin', 'Arbitrator'], true);

        if (!$isParty && !$isAdmin) {
            $this->redirect('/dashboard');
        }

        $messages = $this->dispute->getMessages($id);

        $this->view('dispute/dispute', [
            'dispute'  => $dispute,
            'messages' => $messages,
            'user_id'  => $user_id,
            'role'     => $role,
        ]);
    }

    // ── POST /dispute/open ────────────────────────────────────
    public function open(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dispute');
        }

        $this->requireAuth();

        $project_id   = (int)   ($_POST['project_id']   ?? 0);
        $against      = (int)   ($_POST['against']       ?? 0);
        $reason       = trim($_POST['reason'] ?? '');
        $milestone_id = (int)   ($_POST['milestone_id'] ?? 0) ?: null;
        $user_id      = (int)   $_SESSION['user_id'];

        if (!$project_id || !$against || strlen($reason) < 20) {
            $_SESSION['dispute_error'] = 'Please provide a reason of at least 20 characters.';
            $this->redirect('/dispute');
        }

        $dispute_id = $this->dispute->open($project_id, $user_id, $against, $reason, $milestone_id);

        if (!$dispute_id) {
            $_SESSION['dispute_error'] = 'Could not open dispute. Please try again.';
            $this->redirect('/dispute');
        }

        // Notify the other party
        $this->notif->push(
            $against,
            'dispute_opened',
            'A dispute has been filed against you',
            'A dispute was opened regarding project #' . $project_id . '. Please review.',
            '/dispute?id=' . $dispute_id
        );

        $this->redirect('/dispute?id=' . $dispute_id);
    }

    // ── POST /dispute/message ─────────────────────────────────
    public function addMessage(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dispute');
        }

        $this->requireAuth();

        $dispute_id = (int)   ($_POST['dispute_id'] ?? 0);
        $body       = trim($_POST['body'] ?? '');
        $user_id    = (int)   $_SESSION['user_id'];

        if (!$dispute_id || strlen($body) < 1) {
            $this->redirect('/dispute?id=' . $dispute_id);
        }

        $dispute = $this->dispute->getById($dispute_id);
        if (!$dispute) {
            $this->redirect('/dispute');
        }

        // Only parties and admin may post
        $role    = $_SESSION['role'] ?? '';
        $isParty = ($dispute['raised_by'] == $user_id || $dispute['against'] == $user_id);
        $isAdmin = in_array($role, ['Admin', 'Arbitrator'], true);

        if (!$isParty && !$isAdmin) {
            $this->redirect('/dashboard');
        }

        $this->dispute->addMessage($dispute_id, $user_id, $body);

        // Notify the other party
        $other = ($dispute['raised_by'] == $user_id) ? $dispute['against'] : $dispute['raised_by'];
        $this->notif->push(
            $other,
            'dispute_message',
            'New message in dispute #' . $dispute_id,
            substr($body, 0, 120),
            '/dispute?id=' . $dispute_id
        );

        $this->redirect('/dispute?id=' . $dispute_id);
    }

    // ── POST /dispute/resolve  (Admin/Arbitrator) ─────────────
    public function resolve(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dispute');
        }

        $role = $_SESSION['role'] ?? '';
        if (!in_array($role, ['Admin', 'Arbitrator'], true)) {
            $this->redirect('/dashboard');
        }

        $dispute_id = (int)   ($_POST['dispute_id'] ?? 0);
        $resolution = trim($_POST['resolution'] ?? '');
        $user_id    = (int)   $_SESSION['user_id'];

        if (!$dispute_id || strlen($resolution) < 10) {
            $this->redirect('/dispute?id=' . $dispute_id);
        }

        $this->dispute->resolve($dispute_id, $resolution, $user_id);

        $dispute = $this->dispute->getById($dispute_id);
        if ($dispute) {
            foreach ([$dispute['raised_by'], $dispute['against']] as $uid) {
                $this->notif->push(
                    $uid,
                    'dispute_resolved',
                    'Dispute #' . $dispute_id . ' has been resolved',
                    substr($resolution, 0, 160),
                    '/dispute?id=' . $dispute_id
                );
            }
        }

        $this->redirect('/dispute?id=' . $dispute_id);
    }
}
