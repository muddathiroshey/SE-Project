<?php
namespace App\Models;

class Admin extends Data
{

    public function getDashboardStats(): array
    {
        $conn = $this->getDb();

        $stats = [];

        // Active contracts (projects with status = 'active')
        $r = $conn->query("SELECT COUNT(*) AS cnt FROM projects WHERE status = 'active'");
        $stats['active_contracts'] = (int) $r->fetch_assoc()['cnt'];

        // Total escrowed value
        $r = $conn->query("SELECT COALESCE(SUM(escrow_amount), 0) AS total FROM projects WHERE status = 'active'");
        $stats['escrowed_value'] = (float) $r->fetch_assoc()['total'];

        // Verified specialists
        $r = $conn->query("SELECT COUNT(*) AS cnt FROM userData WHERE user_role = 'Freelancer' AND kyc_status = 'approved'");
        $stats['verified_specialists'] = (int) $r->fetch_assoc()['cnt'];

        // KYC queue size
        $r = $conn->query("SELECT COUNT(*) AS cnt FROM kyc_submissions WHERE status = 'pending'");
        $stats['kyc_queue'] = (int) $r->fetch_assoc()['cnt'];

        // KYC breakdown
        $r = $conn->query(
            "SELECT u.user_role, COUNT(*) AS cnt
             FROM kyc_submissions ks
             JOIN userData u ON u.id = ks.user_id
             WHERE ks.status = 'pending'
             GROUP BY u.user_role"
        );
        $stats['kyc_clients']     = 0;
        $stats['kyc_specialists'] = 0;
        while ($row = $r->fetch_assoc()) {
            if ($row['user_role'] === 'Client')     $stats['kyc_clients']     = (int)$row['cnt'];
            if ($row['user_role'] === 'Freelancer') $stats['kyc_specialists'] = (int)$row['cnt'];
        }

        // Released this month
        $r = $conn->query(
            "SELECT COALESCE(SUM(amount), 0) AS total FROM wallet_transactions
             WHERE type = 'release' AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())"
        );
        $stats['released_this_month'] = (float) $r->fetch_assoc()['total'];

        // Average platform rating
        $r = $conn->query("SELECT ROUND(AVG(rating), 2) AS avg_rating FROM reviews");
        $stats['avg_rating'] = (float) ($r->fetch_assoc()['avg_rating'] ?? 0);

        // Dispute resolution rate  (resolved / total * 100)
        $r = $conn->query("SELECT COUNT(*) AS total FROM disputes");
        $total_disputes = (int) $r->fetch_assoc()['total'];
        $r = $conn->query("SELECT COUNT(*) AS resolved FROM disputes WHERE status = 'resolved'");
        $resolved = (int) $r->fetch_assoc()['resolved'];
        $stats['dispute_resolution_rate'] = $total_disputes > 0
            ? round(($resolved / $total_disputes) * 100, 1)
            : 100.0;

        // Active disputes count (for sidebar badge)
        $r = $conn->query("SELECT COUNT(*) AS cnt FROM disputes WHERE status NOT IN ('resolved','closed')");
        $stats['active_disputes'] = (int) $r->fetch_assoc()['cnt'];

        $conn->close();
        return $stats;
    }

    public function getSystemAlerts(): array
    {
        $conn   = $this->getDb();
        $alerts = [];

        // Disputes approaching SLA deadline (open for > 48 h)
        $stmt = $conn->prepare(
            "SELECT d.id, d.ref_code, p.title AS project_title,
                    u.user_name AS arbitrator_name,
                    TIMESTAMPDIFF(HOUR, d.created_at, NOW()) AS hours_open
             FROM disputes d
             JOIN projects p  ON p.project_id = d.project_id
             LEFT JOIN userData u ON u.id = d.arbitrator_id
             WHERE d.status NOT IN ('resolved','closed')
               AND TIMESTAMPDIFF(HOUR, d.created_at, NOW()) >= 48
             ORDER BY hours_open DESC"
        );
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach ($rows as $row) {
            $alerts[] = [
                'level'   => 'danger',
                'message' => "Dispute SLA at risk: {$row['ref_code']} has been unresolved for {$row['hours_open']}h."
                           . ($row['arbitrator_name'] ? " Arbitrator: {$row['arbitrator_name']}." : ''),
                'action'  => ['label' => 'Escalate', 'url' => '/admin/disputes?id=' . $row['id']],
            ];
        }

        // KYC backlog > 200
        $r = $conn->query("SELECT COUNT(*) AS cnt FROM kyc_submissions WHERE status = 'pending'");
        $kycCount = (int) $r->fetch_assoc()['cnt'];
        if ($kycCount > 200) {
            $alerts[] = [
                'level'   => 'warn',
                'message' => "KYC backlog: {$kycCount} pending verifications exceeds 200-item threshold.",
                'action'  => ['label' => 'Review Queue', 'url' => '/admin/kyc'],
            ];
        }

        $stmt->close();
        $conn->close();
        return $alerts;
    }

    public function getNichePerformance(): array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "SELECT j.category AS niche,
                    COUNT(p.project_id) AS active_count,
                    ROUND(
                        (COUNT(p.project_id) - COUNT(CASE WHEN p.created_at < DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END))
                        / GREATEST(COUNT(CASE WHEN p.created_at < DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END), 1) * 100
                    , 0) AS growth_pct
             FROM projects p
             JOIN jobs j ON j.job_id = p.job_id
             WHERE p.status = 'active'
             GROUP BY j.category
             ORDER BY active_count DESC
             LIMIT 8"
        );
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();

        // Calculate bar width relative to max
        $max = max(array_column($rows, 'active_count') ?: [1]);
        foreach ($rows as &$row) {
            $row['bar_width'] = round(($row['active_count'] / $max) * 100);
        }
        return $rows;
    }

    public function getKycQueue(string $filter = 'all', int $page = 1, int $perPage = 15): array
    {
        $conn   = $this->getDb();
        $offset = ($page - 1) * $perPage;

        $where = "ks.status = 'pending'";
        if ($filter === 'client')     $where .= " AND u.user_role = 'Client'";
        if ($filter === 'specialist') $where .= " AND u.user_role = 'Freelancer'";
        if ($filter === 'high')       $where .= " AND ks.priority = 'high'";

        $sql = "SELECT ks.id, ks.user_id, ks.submitted_at, ks.priority,
                       ks.doc_total, ks.doc_pending,
                       u.user_name, u.user_email, u.user_role,
                       sp.country, sp.specialization AS niche
                FROM kyc_submissions ks
                JOIN userData u ON u.id = ks.user_id
                LEFT JOIN specialists sp ON sp.user_id = ks.user_id
                WHERE {$where}
                ORDER BY
                    FIELD(ks.priority,'high','medium','low'),
                    ks.submitted_at ASC
                LIMIT ? OFFSET ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $perPage, $offset);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Total count for pagination
        $countSql = "SELECT COUNT(*) AS cnt
                     FROM kyc_submissions ks
                     JOIN userData u ON u.id = ks.user_id
                     WHERE {$where}";
        $total = (int) $conn->query($countSql)->fetch_assoc()['cnt'];

        $conn->close();

        return [
            'rows'        => $rows,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => (int) ceil($total / $perPage),
        ];
    }

    /**
     * Single KYC submission detail with uploaded documents.
     */
    public function getKycDetail(int $submissionId): ?array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "SELECT ks.*, u.user_name, u.user_email, u.user_role,
                    sp.country, sp.specialization, sp.bio
             FROM kyc_submissions ks
             JOIN userData u ON u.id = ks.user_id
             LEFT JOIN specialists sp ON sp.user_id = ks.user_id
             WHERE ks.id = ?"
        );
        $stmt->bind_param('i', $submissionId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();

        if ($row) {
            $stmt2 = $conn->prepare(
                "SELECT * FROM kyc_documents WHERE submission_id = ? ORDER BY uploaded_at ASC"
            );
            $stmt2->bind_param('i', $submissionId);
            $stmt2->execute();
            $row['documents'] = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt2->close();
        }

        $conn->close();
        return $row;
    }

    /**
     * Approve or reject a KYC submission.
     *
     * @param int    $submissionId
     * @param string $decision     'approved' | 'rejected'
     * @param string $notes        reviewer notes
     * @param int    $reviewerId   admin user ID
     */
    public function updateKycDecision(int $submissionId, string $decision, string $notes, int $reviewerId): bool
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "UPDATE kyc_submissions
             SET status = ?, reviewer_notes = ?, reviewed_by = ?, reviewed_at = NOW()
             WHERE id = ?"
        );
        $stmt->bind_param('ssii', $decision, $notes, $reviewerId, $submissionId);
        $stmt->execute();
        $ok = $stmt->affected_rows > 0;
        $stmt->close();

        if ($ok) {
            // Propagate approval to the user record
            $stmt2 = $conn->prepare(
                "UPDATE userData SET kyc_status = ? WHERE id =
                 (SELECT user_id FROM kyc_submissions WHERE id = ?)"
            );
            $stmt2->bind_param('si', $decision, $submissionId);
            $stmt2->execute();
            $stmt2->close();
        }

        $conn->close();
        return $ok;
    }

    public function getSanctions(): array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "SELECT s.id, s.tier, s.reason, s.message, s.duration_days,
                    s.created_at, s.expires_at,
                    u.id AS user_id, u.user_name, u.user_email, u.user_role,
                    sp.specialization AS niche
             FROM sanctions s
             JOIN userData u ON u.id = s.user_id
             LEFT JOIN specialists sp ON sp.user_id = s.user_id
             WHERE s.status = 'active'
             ORDER BY s.created_at DESC"
        );
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();

        $grouped = ['warnings' => [], 'limited_bans' => [], 'permanent_bans' => []];
        foreach ($rows as $row) {
            if ($row['tier'] === 'warning')      $grouped['warnings'][]       = $row;
            if ($row['tier'] === 'limited_ban')  $grouped['limited_bans'][]   = $row;
            if ($row['tier'] === 'permanent_ban')$grouped['permanent_bans'][] = $row;
        }
        return $grouped;
    }

    /**
     * Apply a new sanction to a user.
     */
    public function createSanction(int $userId, string $tier, string $reason, string $message, ?int $durationDays, int $issuedBy): bool
    {
        $conn = $this->getDb();
        $expiresAt = null;
        if ($durationDays !== null) {
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$durationDays} days"));
        }
        $stmt = $conn->prepare(
            "INSERT INTO sanctions (user_id, tier, reason, message, duration_days, expires_at, issued_by, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'active', NOW())"
        );
        $stmt->bind_param('issssii', $userId, $tier, $reason, $message, $durationDays, $expiresAt, $issuedBy);
        $stmt->execute();
        $ok = $stmt->affected_rows > 0;
        $stmt->close();
        $conn->close();
        return $ok;
    }

    /**
     * Withdraw (lift) a sanction and record the withdrawal reason.
     */
    public function withdrawSanction(int $sanctionId, string $withdrawalMessage, int $adminId): bool
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "UPDATE sanctions SET status = 'withdrawn', withdrawal_message = ?, withdrawn_by = ?, withdrawn_at = NOW()
             WHERE id = ?"
        );
        $stmt->bind_param('sii', $withdrawalMessage, $adminId, $sanctionId);
        $stmt->execute();
        $ok = $stmt->affected_rows > 0;
        $stmt->close();
        $conn->close();
        return $ok;
    }

    public function searchUsers(string $query, int $limit = 10): array
    {
        $conn = $this->getDb();
        $q    = '%' . $conn->real_escape_string($query) . '%';
        $stmt = $conn->prepare(
            "SELECT id, user_name, user_email, user_role FROM userData
             WHERE user_role != 'Admin' AND (user_name LIKE ? OR user_email LIKE ?)
             LIMIT ?"
        );
        $stmt->bind_param('ssi', $q, $q, $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    public function getActiveDisputes(): array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "SELECT d.id, d.ref_code, d.status, d.created_at,
                    TIMESTAMPDIFF(HOUR, d.created_at, NOW()) AS hours_open,
                    p.title AS project_title,
                    u_r.user_name AS raised_by_name,
                    u_a.user_name AS against_name,
                    u_arb.user_name AS arbitrator_name
             FROM disputes d
             JOIN projects p        ON p.project_id = d.project_id
             JOIN userData u_r      ON u_r.id = d.raised_by
             JOIN userData u_a      ON u_a.id = d.against
             LEFT JOIN userData u_arb ON u_arb.id = d.arbitrator_id
             WHERE d.status NOT IN ('resolved','closed')
             ORDER BY hours_open DESC"
        );
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }
}