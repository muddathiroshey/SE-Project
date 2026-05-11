<?php
namespace App\Models;

class Notification extends Data
{
    /**
     * Get all notifications for a user, newest first.
     */
    public function getForUser(int $user_id, int $limit = 100): array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            'SELECT * FROM notifications WHERE user_id = ?
             ORDER BY created_at DESC LIMIT ?'
        );
        $stmt->bind_param('ii', $user_id, $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    /**
     * Count of unread notifications.
     */
    public function countUnread(int $user_id): int
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            'SELECT COUNT(*) AS cnt FROM notifications WHERE user_id = ? AND is_read = 0'
        );
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();
        return (int) ($row['cnt'] ?? 0);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(int $id, int $user_id): bool
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            'UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?'
        );
        $stmt->bind_param('ii', $id, $user_id);
        $stmt->execute();
        $ok = $stmt->affected_rows > 0;
        $stmt->close();
        $conn->close();
        return $ok;
    }

    /**
     * Mark ALL notifications as read for a user.
     */
    public function markAllRead(int $user_id): void
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            'UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0'
        );
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    /**
     * Dismiss (delete) a single notification.
     */
    public function dismiss(int $id, int $user_id): bool
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            'DELETE FROM notifications WHERE id = ? AND user_id = ?'
        );
        $stmt->bind_param('ii', $id, $user_id);
        $stmt->execute();
        $ok = $stmt->affected_rows > 0;
        $stmt->close();
        $conn->close();
        return $ok;
    }

    /**
     * Push a new notification to a user.
     *
     * @param string $type   e.g. 'bid_received', 'milestone_approved', 'dispute_opened'
     * @param string $title  Short headline
     * @param string $body   Longer description (optional)
     * @param string $link   Relative URL the user should land on when they click
     */
    public function push(int $user_id, string $type, string $title, string $body = '', string $link = ''): int
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            'INSERT INTO notifications (user_id, type, title, body, link)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('issss', $user_id, $type, $title, $body, $link);
        $stmt->execute();
        $id = $stmt->affected_rows > 0 ? $conn->insert_id : 0;
        $stmt->close();
        $conn->close();
        return $id;
    }
}
