<?php
namespace App\Models;

class Dispute extends Data
{
    // ── READ ──────────────────────────────────────────────────

    /**
     * Single dispute by ID, with full party info.
     */
    public function getById(int $id): ?array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "SELECT d.*,
                    p.title         AS project_title,
                    u_r.user_name   AS raised_by_name,
                    u_a.user_name   AS against_name,
                    u_arb.user_name AS arbitrator_name,
                    pm.milestone_name
             FROM disputes d
             JOIN projects p        ON p.project_id = d.project_id
             JOIN userData u_r      ON u_r.id = d.raised_by
             JOIN userData u_a      ON u_a.id = d.against
             LEFT JOIN userData u_arb ON u_arb.id = d.arbitrator_id
             LEFT JOIN project_milestones pm ON pm.id = d.milestone_id
             WHERE d.id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        $conn->close();
        return $row;
    }

    /**
     * Disputes involving the authenticated user (either party).
     */
    public function getForUser(int $user_id): array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "SELECT d.*, p.title AS project_title,
                    u_r.user_name AS raised_by_name,
                    u_a.user_name AS against_name
             FROM disputes d
             JOIN projects p   ON p.project_id = d.project_id
             JOIN userData u_r ON u_r.id = d.raised_by
             JOIN userData u_a ON u_a.id = d.against
             WHERE d.raised_by = ? OR d.against = ?
             ORDER BY d.created_at DESC"
        );
        $stmt->bind_param('ii', $user_id, $user_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    /**
     * Thread of messages for a dispute.
     */
    public function getMessages(int $dispute_id): array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            'SELECT dm.*, u.user_name, u.user_role
             FROM dispute_messages dm
             JOIN userData u ON u.id = dm.user_id
             WHERE dm.dispute_id = ?
             ORDER BY dm.created_at ASC'
        );
        $stmt->bind_param('i', $dispute_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    // ── WRITE ─────────────────────────────────────────────────

    /**
     * Open a new dispute. Returns new dispute ID.
     */
    public function open(int $project_id, int $raised_by, int $against, string $reason, ?int $milestone_id = null): int
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            'INSERT INTO disputes (project_id, raised_by, against, reason, milestone_id)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('iiisi', $project_id, $raised_by, $against, $reason, $milestone_id);
        $stmt->execute();
        $id = $stmt->affected_rows > 0 ? $conn->insert_id : 0;
        $stmt->close();

        // Lock the project into disputed status
        if ($id) {
            $upd = $conn->prepare(
                "UPDATE projects SET status = 'disputed' WHERE project_id = ?"
            );
            $upd->bind_param('i', $project_id);
            $upd->execute();
            $upd->close();
        }

        $conn->close();
        return $id;
    }

    /**
     * Add a message to the dispute thread.
     */
    public function addMessage(int $dispute_id, int $user_id, string $body): int
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            'INSERT INTO dispute_messages (dispute_id, user_id, body) VALUES (?, ?, ?)'
        );
        $stmt->bind_param('iis', $dispute_id, $user_id, $body);
        $stmt->execute();
        $id = $stmt->affected_rows > 0 ? $conn->insert_id : 0;
        $stmt->close();
        $conn->close();
        return $id;
    }

    /**
     * Resolve a dispute (Admin/Arbitrator only).
     */
    public function resolve(int $id, string $resolution, int $resolver_id): bool
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "UPDATE disputes
             SET status = 'resolved', resolution = ?, arbitrator_id = ?, resolved_at = NOW()
             WHERE id = ?"
        );
        $stmt->bind_param('sii', $resolution, $resolver_id, $id);
        $stmt->execute();
        $ok = $stmt->affected_rows > 0;
        $stmt->close();
        $conn->close();
        return $ok;
    }
}
