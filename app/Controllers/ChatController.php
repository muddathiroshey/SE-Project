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

        $threads  = $this->getThreads($user_id);
        $messages = [];
        $partner  = null;

        if ($with_user_id) {
            $messages = $this->getMessages($user_id, $with_user_id, $project_id);
            $partner  = $this->getUserById($with_user_id);
            $this->markRead($user_id, $with_user_id);
        }

        $this->view('chat/chat', [
            'threads'       => $threads,
            'messages'      => $messages,
            'partner'       => $partner,
            'with_user_id'  => $with_user_id,
            'project_id'    => $project_id,
            'csrf_token'    => $this->generateCsrf(),
        ]);
    }

    // ── POST /chat/send ───────────────────────────────────────
    public function send(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/chat');
        }

        $this->requireAuth();
        $this->verifyCsrf($_POST['csrf_token'] ?? '');

        $sender_id   = (int) $_SESSION['user_id'];
        $receiver_id = (int) ($_POST['receiver_id'] ?? 0);
        $body        = trim($_POST['body'] ?? '');
        $project_id  = (int) ($_POST['project_id'] ?? 0) ?: null;

        if (!$receiver_id || $body === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Missing receiver or message body.']);
            exit();
        }

        // Handle optional file attachment
        $attachment_path = null;
        $attachment_name = null;
        if (!empty($_FILES['attachment']['tmp_name'])) {
            $upload = $this->storeAttachment($_FILES['attachment']);
            if ($upload) {
                $attachment_path = $upload['path'];
                $attachment_name = $upload['name'];
            }
        }

        $stmt = $this->db->prepare(
            'INSERT INTO messages (sender_id, receiver_id, project_id, body, attachment_path, attachment_name)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('iiisss', $sender_id, $receiver_id, $project_id, $body, $attachment_path, $attachment_name);
        $stmt->execute();
        $new_id = (int) $this->db->insert_id;
        $stmt->close();

        // AJAX request: return JSON
        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode([
                'id'              => $new_id,
                'sender_id'       => $sender_id,
                'body'            => $body,
                'attachment_path' => $attachment_path,
                'attachment_name' => $attachment_name,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
            exit();
        }

        // Full-page fallback
        $back = '/chat?with=' . $receiver_id;
        if ($project_id) {
            $back .= '&project=' . $project_id;
        }
        $this->redirect($back);
    }

    // ── AJAX GET /chat/poll?with={id}&since={msg_id} ──────────
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

        // Mark incoming messages read while we're here
        $this->markRead($user_id, $with_user_id);

        $stmt = $this->db->prepare(
            'SELECT m.id, m.sender_id, m.body, m.attachment_path, m.attachment_name, m.created_at,
                    u.user_name AS sender_name
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
        // Use a subquery to get the actual latest message body (not MAX of all bodies)
        $stmt = $this->db->prepare(
            "SELECT
                t.partner_id,
                t.last_msg_id,
                t.last_at,
                t.unread_count,
                t.partner_name,
                m.body AS preview
             FROM (
                SELECT
                    partner_id,
                    MAX(id)         AS last_msg_id,
                    MAX(created_at) AS last_at,
                    SUM(unread)     AS unread_count,
                    MAX(partner_name) AS partner_name
                FROM (
                    SELECT
                        CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END AS partner_id,
                        id,
                        created_at,
                        (is_read = 0 AND receiver_id = ?) AS unread,
                        u.user_name AS partner_name
                    FROM messages msg
                    JOIN userData u ON u.id = CASE WHEN msg.sender_id = ? THEN msg.receiver_id ELSE msg.sender_id END
                    WHERE msg.sender_id = ? OR msg.receiver_id = ?
                ) inner_t
                GROUP BY partner_id
             ) t
             JOIN messages m ON m.id = t.last_msg_id
             ORDER BY t.last_at DESC"
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
                'SELECT m.id, m.sender_id, m.body, m.attachment_path, m.attachment_name, m.created_at,
                        u.user_name AS sender_name
                 FROM messages m
                 JOIN userData u ON u.id = m.sender_id
                 WHERE m.project_id = ?
                   AND (m.sender_id IN (?,?) AND m.receiver_id IN (?,?))
                 ORDER BY m.created_at ASC LIMIT 200'
            );
            $stmt->bind_param('iiiii', $project_id, $user_id, $partner_id, $user_id, $partner_id);
        } else {
            $stmt = $this->db->prepare(
                'SELECT m.id, m.sender_id, m.body, m.attachment_path, m.attachment_name, m.created_at,
                        u.user_name AS sender_name
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

    private function storeAttachment(array $file): ?array
    {
        $allowed_mime = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'application/zip',
        ];

        $max_bytes = 10 * 1024 * 1024; // 10 MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        if ($file['size'] > $max_bytes) {
            return null;
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowed_mime, true)) {
            return null;
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safe_ext = preg_replace('/[^a-zA-Z0-9]/', '', $ext);
        $filename = bin2hex(random_bytes(16)) . ($safe_ext ? '.' . $safe_ext : '');
        $dir      = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/uploads/chat/';

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $dest = $dir . $filename;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return null;
        }

        return [
            'path' => '/uploads/chat/' . $filename,
            'name' => htmlspecialchars($file['name'], ENT_QUOTES, 'UTF-8'),
        ];
    }

    private function isAjax(): bool
    {
        return (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest')
            || (($_SERVER['HTTP_ACCEPT'] ?? '') === 'application/json');
    }

    private function generateCsrf(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private function verifyCsrf(string $token): void
    {
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            exit('Invalid CSRF token.');
        }
    }
}