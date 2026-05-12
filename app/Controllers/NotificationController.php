<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    private Notification $notif;

    public function __construct()
    {
        parent::__construct();
        $this->notif = new Notification();
    }


    // ── GET /notifications ────────────────────────────────────
    public function index(): void
    {
        $this->requireAuth();

        $user_id       = (int) $_SESSION['user_id'];
        $notifications = $this->notif->getForUser($user_id);
        $unread_count  = $this->notif->countUnread($user_id);

        $this->view('notifications/notifications', [
            'notifications' => $notifications,
            'unread_count'  => $unread_count,
        ]);
    }

    // ── POST /notifications/read  (mark one read) ─────────────
    public function markRead(): void
    {
        $this->requireAuth();

        $id      = (int) ($_POST['id'] ?? 0);
        $user_id = (int) $_SESSION['user_id'];

        if ($id) {
            $this->notif->markRead($id, $user_id);
        }

        // AJAX shortcut
        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => true]);
            exit();
        }

        $this->redirect('/notifications');
    }

    // ── POST /notifications/read-all ──────────────────────────
    public function markAllRead(): void
    {
        $this->requireAuth();
        $this->notif->markAllRead((int) $_SESSION['user_id']);

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => true]);
            exit();
        }

        $this->redirect('/notifications/notifications');
    }

    // ── POST /notifications/dismiss ───────────────────────────
    public function dismiss(): void
    {
        $this->requireAuth();

        $id      = (int) ($_POST['id'] ?? 0);
        $user_id = (int) $_SESSION['user_id'];

        if ($id) {
            $this->notif->dismiss($id, $user_id);
        }

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => true]);
            exit();
        }

        $this->redirect('/notifications');
    }

    // ── GET /notifications/count  (AJAX badge refresh) ────────
    public function count(): void
    {
        $this->requireAuth();
        header('Content-Type: application/json');
        echo json_encode([
            'count' => $this->notif->countUnread((int) $_SESSION['user_id']),
        ]);
        exit();
    }

    // ── helper ────────────────────────────────────────────────
    private function isAjax(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }
}
