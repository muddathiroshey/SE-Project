<?php
namespace App\Models;

class Job extends Data
{
    // ── BROWSE (Specialist side) ──────────────────────────────

    /**
     * Paginated list of open project_postings for the browse-jobs page.
     *
     * Filters: niche, budget_min, budget_max, q (keyword search), page
     */
    public function browse(array $filters = [], int $page = 1, int $perPage = 15): array
    {
        $conn   = $this->getDb();
        $where  = ["pp.status = 'posted'", "pp.visibility = 'public'"];
        $params = [];
        $types  = '';

        if (!empty($filters['niche'])) {
            $where[]  = 'pp.niche = ?';
            $params[] = $filters['niche'];
            $types   .= 's';
        }

        if (!empty($filters['budget_min'])) {
            $where[]  = 'pp.total_budget >= ?';
            $params[] = (float) $filters['budget_min'];
            $types   .= 'd';
        }

        if (!empty($filters['budget_max'])) {
            $where[]  = 'pp.total_budget <= ?';
            $params[] = (float) $filters['budget_max'];
            $types   .= 'd';
        }

        if (!empty($filters['q'])) {
            $like     = '%' . $filters['q'] . '%';
            $where[]  = '(pp.project_title LIKE ? OR pp.project_brief LIKE ?)';
            $params[] = $like;
            $params[] = $like;
            $types   .= 'ss';
        }

        $whereSQL = implode(' AND ', $where);
        $offset   = ($page - 1) * $perPage;

        // Count
        $countSQL  = "SELECT COUNT(*) AS total FROM project_postings pp WHERE {$whereSQL}";
        $cstmt     = $conn->prepare($countSQL);
        if ($types) {
            $cstmt->bind_param($types, ...$params);
        }
        $cstmt->execute();
        $total = (int) $cstmt->get_result()->fetch_assoc()['total'];
        $cstmt->close();

        // Data
        $sql = "SELECT pp.*,
                       COALESCE(cp.org_name, u.user_name) AS client_display_name,
                       cp.reputation_score                  AS client_rating,
                       cp.projects_completed                AS client_projects,
                       u.is_verified                        AS client_verified,
                       (SELECT COUNT(*) FROM bids b WHERE b.job_id = pp.id) AS bid_count
                FROM project_postings pp
                JOIN clientProfile cp ON cp.id = pp.client_id
                JOIN userData u        ON u.id  = pp.user_id
                WHERE {$whereSQL}
                ORDER BY pp.created_at DESC
                LIMIT ? OFFSET ?";

        $params[] = $perPage;
        $params[] = $offset;
        $types   .= 'ii';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();

        return ['data' => $rows, 'total' => $total, 'pages' => (int) ceil($total / $perPage)];
    }

    /**
     * All distinct niches with posting counts, for the filter sidebar.
     */
    public function getNichesWithCounts(): array
    {
        $conn = $this->getDb();
        $result = $conn->query(
            "SELECT niche, COUNT(*) AS cnt
             FROM project_postings WHERE status='posted'
             GROUP BY niche ORDER BY cnt DESC"
        );
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $conn->close();
        return $rows;
    }

    /**
     * Single posting detail.
     */
    public function getById(int $id): ?array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "SELECT pp.*,
                    COALESCE(cp.org_name, u.user_name) AS client_display_name,
                    cp.reputation_score  AS client_rating,
                    cp.projects_completed AS client_projects,
                    u.is_verified        AS client_verified,
                    (SELECT COUNT(*) FROM bids b WHERE b.job_id = pp.id) AS bid_count
             FROM project_postings pp
             JOIN clientProfile cp ON cp.id  = pp.client_id
             JOIN userData u        ON u.id   = pp.user_id
             WHERE pp.id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        $conn->close();

        if ($row) {
            $row['milestones']    = json_decode($row['milestones_json']    ?? '[]', true) ?: [];
            $row['niche_answers'] = json_decode($row['niche_answers_json'] ?? '[]', true) ?: [];
        }
        return $row;
    }

    // ── CLIENT SIDE ───────────────────────────────────────────

    /**
     * All postings by a client (for dashboard / incoming-bids).
     */
    public function getByClientId(int $client_id): array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "SELECT pp.*,
                    (SELECT COUNT(*) FROM bids b WHERE b.job_id = pp.id) AS bid_count,
                    (SELECT COUNT(*) FROM bids b WHERE b.job_id = pp.id AND b.status='submitted') AS new_bids
             FROM project_postings pp
             WHERE pp.client_id = ?
             ORDER BY pp.created_at DESC"
        );
        $stmt->bind_param('i', $client_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    /**
     * All bids across all of a client's postings (incoming-bids page).
     */
    public function getIncomingBids(int $client_id, array $filters = []): array
    {
        $conn  = $this->getDb();
        $where = ['pp.client_id = ?'];
        $params = [$client_id];
        $types  = 'i';

        if (!empty($filters['status'])) {
            $where[]  = 'b.status = ?';
            $params[] = $filters['status'];
            $types   .= 's';
        }

        if (!empty($filters['job_id'])) {
            $where[]  = 'b.job_id = ?';
            $params[] = (int) $filters['job_id'];
            $types   .= 'i';
        }

        $whereSQL = implode(' AND ', $where);

        $stmt = $conn->prepare(
            "SELECT b.*,
                    pp.project_title   AS job_title,
                    pp.niche           AS job_niche,
                    pp.total_budget    AS job_budget,
                    u.user_name        AS specialist_name,
                    u.is_verified      AS specialist_verified,
                    sp.primary_niche   AS specialist_niche,
                    sp.rating_avg      AS specialist_rating,
                    sp.project_number  AS specialist_projects,
                    (SELECT COUNT(*) FROM bid_milestones bm WHERE bm.bid_id = b.id) AS milestones_count
             FROM bids b
             JOIN project_postings pp ON pp.id   = b.job_id
             JOIN userData u           ON u.id    = b.user_id
             LEFT JOIN specialistProfiles sp ON sp.user_id = b.user_id
             WHERE {$whereSQL}
             ORDER BY b.submitted_at DESC"
        );
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    // ── SPECIALIST: MY BIDS ───────────────────────────────────

    /**
     * All bids submitted by a specialist.
     */
    public function getMyBids(int $user_id, string $status = ''): array
    {
        $conn   = $this->getDb();
        $where  = ['b.user_id = ?'];
        $params = [$user_id];
        $types  = 'i';

        if ($status !== '') {
            $where[]  = 'b.status = ?';
            $params[] = $status;
            $types   .= 's';
        }

        $whereSQL = implode(' AND ', $where);

        $stmt = $conn->prepare(
            "SELECT b.*,
                    pp.project_title  AS job_title,
                    pp.niche          AS job_niche,
                    pp.total_budget   AS job_budget,
                    COALESCE(cp.org_name, uc.user_name) AS client_name,
                    uc.is_verified    AS client_verified,
                    (SELECT COUNT(*) FROM bid_milestones bm WHERE bm.bid_id = b.id) AS milestones_count
             FROM bids b
             JOIN project_postings pp ON pp.id = b.job_id
             JOIN clientProfile cp    ON cp.id = pp.client_id
             JOIN userData uc         ON uc.id = cp.user_id
             WHERE {$whereSQL}
             ORDER BY b.submitted_at DESC"
        );
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    /**
     * Bid stats for the my-bids hero.
     */
    public function getBidStats(int $user_id): array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(status IN ('submitted','shortlisted')) AS active,
                SUM(status = 'accepted')   AS accepted,
                SUM(status = 'withdrawn')  AS withdrawn,
                SUM(status = 'rejected')   AS declined
             FROM bids WHERE user_id = ?"
        );
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();

        $row['acceptance_rate'] = $row['total'] > 0
            ? round(($row['accepted'] / $row['total']) * 100, 1)
            : 0;

        return $row;
    }

    // ── BROWSE EXPERTS (Client side) ──────────────────────────

    /**
     * Paginated specialist search for the browse-experts page.
     */
    public function browseExperts(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        $conn   = $this->getDb();
        $where  = ["sp.profile_status = 'approved'", "u.is_active = 1"];
        $params = [];
        $types  = '';

        if (!empty($filters['niche'])) {
            $where[]  = 'sp.primary_niche = ?';
            $params[] = $filters['niche'];
            $types   .= 's';
        }

        if (!empty($filters['q'])) {
            $like     = '%' . $filters['q'] . '%';
            $where[]  = '(u.user_name LIKE ? OR sp.primary_niche LIKE ? OR sp.summary LIKE ?)';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $types   .= 'sss';
        }

        if (!empty($filters['verified_only'])) {
            $where[] = 'u.is_verified = 1';
        }

        $whereSQL = implode(' AND ', $where);
        $offset   = ($page - 1) * $perPage;

        $countSQL = "SELECT COUNT(*) AS total FROM specialistProfiles sp JOIN userData u ON u.id = sp.user_id WHERE {$whereSQL}";
        $cstmt    = $conn->prepare($countSQL);
        if ($types) {
            $cstmt->bind_param($types, ...$params);
        }
        $cstmt->execute();
        $total = (int) $cstmt->get_result()->fetch_assoc()['total'];
        $cstmt->close();

        $sql = "SELECT sp.*, u.user_name, u.is_verified, u.user_email,
                       GROUP_CONCAT(ss.skill_name ORDER BY ss.skill_name SEPARATOR ', ') AS skills_csv
                FROM specialistProfiles sp
                JOIN userData u ON u.id = sp.user_id
                LEFT JOIN specialistSkills ss ON ss.user_id = sp.user_id
                WHERE {$whereSQL}
                GROUP BY sp.id
                ORDER BY sp.rating_avg DESC, sp.project_number DESC
                LIMIT ? OFFSET ?";

        $params[] = $perPage;
        $params[] = $offset;
        $types   .= 'ii';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();

        return ['data' => $rows, 'total' => $total, 'pages' => (int) ceil($total / $perPage)];
    }
}
