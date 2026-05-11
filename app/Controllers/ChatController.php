<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Data;

class ChatController extends Controller
{
    private \mysqli $db;

    public function __construct()
    {
        parent::__construct();
        $data     = new Data();
        $this->db = $data->getDb();
    }

    // ── GET /chat ─────────────────────────────────────────────
    public function index(): void
    {
        $this->requireAuth();

        $user_id      = (int) $_SESSION['user_id'];
        $with_user_id = (int) ($_GET['with'] ?? 0);
        $project_id   = (int) ($_GET['project'] ?? 0) ?: null;

        // Threads: list of unique conversation partners
        $threads = $this->getThreads($user_id);

        // Active thread messages
        $messages = [];
        $partner  = null;
        if ($with_user_id) {
            $messages = $this->getMessages($user_id, $with_user_id, $project_id);
            $partner  = $this->getUserById($with_user_id);
            // Mark as read
            $this->markRead($user_id, $with_user_id);
        }

        $this->view('chat', [
            'threads'       => $threads,
            'messages'      => $messages,
            'partner'       => $partner,
            'with_user_id'  => $with_user_id,
            'project_id'    => $project_id,
        ]);
    }

    // ── POST /chat/send ───────────────────────────────────────
    public function send(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/chat');
        }

        $this->requireAuth();

        $sender_id   = (int)   $_SESSION['user_id'];
        $receiver_id = (int)   ($_POST['receiver_id'] ?? 0);
        $body        = trim($_POST['body'] ?? '');
        $project_id  = (int)   ($_POST['project_id']  ?? 0) ?: null;

        if (!$receiver_id || $body === '') {
            $this->redirect('/chat');
        }

        $stmt = $this->db->prepare(
            'INSERT INTO messages (sender_id, receiver_id, project_id, body)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->bind_param('iiis', $sender_id, $receiver_id, $project_id, $body);
        $stmt->execute();
        $stmt->close();

        $back = '/chat?with=' . $receiver_id;
        if ($project_id) {
            $back .= '&project=' . $project_id;
        }
        $this->redirect($back);
    }

    // ── AJAX GET /chat/messages?with={id} ─────────────────────
    public function poll(): void
    {
        $this->requireAuth();
        header('Content-Type: application/json');

        $user_id      = (int) $_SESSION['user_id'];
        $with_user_id = (int) ($_GET['with'] ?? 0);
        $since_id     = (int) ($_GET['since'] ?? 0);

        if (!$with_user_id) {
            echo json_encode([]);
            exit();
        }

        $stmt = $this->db->prepare(
            'SELECT m.*, u.user_name AS sender_name
             FROM messages m
             JOIN userData u ON u.id = m.sender_id
             WHERE ((m.sender_id = ? AND m.receiver_id = ?)
                 OR (m.sender_id = ? AND m.receiver_id = ?))
               AND m.id > ?
             ORDER BY m.created_at ASC
             LIMIT 50'
        );
        $stmt->bind_param('iiiii', $user_id, $with_user_id, $with_user_id, $user_id, $since_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        echo json_encode($rows);
        exit();
    }

    // ── private helpers ───────────────────────────────────────

    private function getThreads(int $user_id): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                partner_id,
                MAX(id)          AS last_msg_id,
                MAX(created_at)  AS last_at,
                SUM(unread)      AS unread_count,
                MAX(body_preview) AS preview,
                MAX(partner_name) AS partner_name
             FROM (
                SELECT
                    CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END AS partner_id,
                    id,
                    created_at,
                    (is_read = 0 AND receiver_id = ?) AS unread,
                    SUBSTRING(body, 1, 80)             AS body_preview,
                    u.user_name                        AS partner_name
                FROM messages m
                JOIN userData u ON u.id = CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END
                WHERE m.sender_id = ? OR m.receiver_id = ?
             ) t
             GROUP BY partner_id
             ORDER BY last_at DESC"
        );
        $stmt->bind_param('iiiii', $user_id, $user_id, $user_id, $user_id, $user_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    private function getMessages(int $user_id, int $partner_id, ?int $project_id): array
    {
        if ($project_id) {
            $stmt = $this->db->prepare(
                'SELECT m.*, u.user_name AS sender_name
                 FROM messages m
                 JOIN userData u ON u.id = m.sender_id
                 WHERE m.project_id = ?
                   AND (m.sender_id IN (?,?) AND m.receiver_id IN (?,?))
                 ORDER BY m.created_at ASC LIMIT 200'
            );
            $stmt->bind_param('iiiii', $project_id, $user_id, $partner_id, $user_id, $partner_id);
        } else {
            $stmt = $this->db->prepare(
                'SELECT m.*, u.user_name AS sender_name
                 FROM messages m
                 JOIN userData u ON u.id = m.sender_id
                 WHERE (m.sender_id = ? AND m.receiver_id = ?)
                    OR (m.sender_id = ? AND m.receiver_id = ?)
                 ORDER BY m.created_at ASC LIMIT 200'
            );
            $stmt->bind_param('iiii', $user_id, $partner_id, $partner_id, $user_id);
        }

        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    private function markRead(int $user_id, int $sender_id): void
    {
        $stmt = $this->db->prepare(
            'UPDATE messages SET is_read = 1, read_at = NOW()
             WHERE sender_id = ? AND receiver_id = ? AND is_read = 0'
        );
        $stmt->bind_param('ii', $sender_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    private function getUserById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, user_name, user_role FROM userData WHERE id = ?'
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $row;
    }
}
