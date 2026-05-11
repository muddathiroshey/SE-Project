<?php
namespace App\Models;

class Wallet extends Data
{
    // ── READ ──────────────────────────────────────────────────

    /**
     * Get or create a wallet row for a user.
     */
    public function getOrCreate(int $user_id): array
    {
        $conn = $this->getDb();

        $stmt = $conn->prepare('SELECT * FROM wallets WHERE user_id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row) {
            $ins = $conn->prepare(
                'INSERT INTO wallets (user_id, balance, pending, currency) VALUES (?, 0.00, 0.00, "USD")'
            );
            $ins->bind_param('i', $user_id);
            $ins->execute();
            $id  = $conn->insert_id;
            $ins->close();

            $stmt = $conn->prepare('SELECT * FROM wallets WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();
        }

        $conn->close();
        return $row;
    }

    /**
     * Recent transactions for a user (latest 50).
     */
    public function getTransactions(int $user_id, int $limit = 50): array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            'SELECT wt.*, p.title AS project_title
             FROM wallet_transactions wt
             LEFT JOIN projects p ON p.project_id = wt.project_id
             WHERE wt.user_id = ?
             ORDER BY wt.created_at DESC
             LIMIT ?'
        );
        $stmt->bind_param('ii', $user_id, $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    /**
     * Active escrow records (per project/milestone) for a client.
     */
    public function getActiveEscrow(int $user_id): array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "SELECT e.*, p.title AS project_title,
                    pm.milestone_name, pm.sort_order AS milestone_order,
                    u_sp.user_name AS specialist_name
             FROM escrow e
             JOIN projects p ON p.project_id = e.project_id
             JOIN clientProfile cp ON cp.id = e.client_id
             LEFT JOIN project_milestones pm ON pm.id = e.milestone_id
             JOIN specialistProfiles sp ON sp.id = e.specialist_id
             JOIN userData u_sp ON u_sp.id = sp.user_id
             WHERE cp.user_id = ? AND e.status IN ('held','disputed')
             ORDER BY e.created_at DESC"
        );
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    /**
     * YTD summary for client wallet hero.
     */
    public function getClientSummary(int $user_id): array
    {
        $conn = $this->getDb();

        // total_spent, ytd_released, in_escrow, pending_release, frozen
        $stmt = $conn->prepare(
            "SELECT
                COALESCE(SUM(CASE WHEN wt.type = 'escrow_hold' THEN wt.amount ELSE 0 END), 0)            AS total_spent,
                COALESCE(SUM(CASE WHEN wt.type = 'escrow_release' AND YEAR(wt.created_at) = YEAR(NOW()) THEN wt.amount ELSE 0 END), 0) AS ytd_released,
                (SELECT COALESCE(SUM(amount),0) FROM escrow e JOIN clientProfile cp2 ON cp2.id=e.client_id WHERE cp2.user_id=? AND e.status='held')      AS in_escrow,
                (SELECT COALESCE(SUM(amount),0) FROM escrow e JOIN clientProfile cp2 ON cp2.id=e.client_id WHERE cp2.user_id=? AND e.status='released')  AS pending_release,
                (SELECT COALESCE(SUM(amount),0) FROM escrow e JOIN clientProfile cp2 ON cp2.id=e.client_id WHERE cp2.user_id=? AND e.status='disputed')  AS frozen
             FROM wallet_transactions wt
             WHERE wt.user_id = ?"
        );
        $stmt->bind_param('iiii', $user_id, $user_id, $user_id, $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();

        return $row ?? [
            'total_spent'     => 0,
            'ytd_released'    => 0,
            'in_escrow'       => 0,
            'pending_release' => 0,
            'frozen'          => 0,
        ];
    }

    /**
     * Earnings summary for specialist wallet hero.
     */
    public function getSpecialistSummary(int $user_id): array
    {
        $conn = $this->getDb();

        $stmt = $conn->prepare(
            "SELECT
                w.balance                                                                                      AS cleared,
                w.pending                                                                                      AS pending,
                COALESCE(SUM(CASE WHEN wt.type='escrow_release' AND YEAR(wt.created_at)=YEAR(NOW()) THEN wt.amount ELSE 0 END),0) AS ytd,
                COALESCE(SUM(CASE WHEN wt.type='escrow_release' THEN wt.amount ELSE 0 END),0)                AS lifetime,
                COALESCE(SUM(CASE WHEN wt.type='escrow_release' AND DATE_FORMAT(wt.created_at,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m') THEN wt.amount ELSE 0 END),0) AS this_month,
                COALESCE(SUM(CASE WHEN wt.type='escrow_release' AND DATE_FORMAT(wt.created_at,'%Y-%m')=DATE_FORMAT(NOW() - INTERVAL 1 MONTH,'%Y-%m') THEN wt.amount ELSE 0 END),0) AS last_month
             FROM wallets w
             LEFT JOIN wallet_transactions wt ON wt.user_id = w.user_id
             WHERE w.user_id = ?
             GROUP BY w.id"
        );
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();

        return $row ?? [
            'cleared'    => 0,
            'pending'    => 0,
            'ytd'        => 0,
            'lifetime'   => 0,
            'this_month' => 0,
            'last_month' => 0,
        ];
    }

    // ── WRITE ─────────────────────────────────────────────────

    /**
     * Log a wallet transaction and update balance atomically.
     */
    public function addTransaction(int $user_id, string $type, float $amount, ?int $project_id, string $description): bool
    {
        $conn = $this->getDb();
        $conn->begin_transaction();

        try {
            // lock the row
            $stmt = $conn->prepare('SELECT balance, pending FROM wallets WHERE user_id = ? FOR UPDATE');
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $wallet = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$wallet) {
                throw new \RuntimeException('Wallet not found for user ' . $user_id);
            }

            $balance = (float) $wallet['balance'];
            $pending = (float) $wallet['pending'];

            // Adjust balances by transaction type
            switch ($type) {
                case 'deposit':
                    $balance += $amount;
                    break;
                case 'withdrawal':
                    $balance -= $amount;
                    break;
                case 'escrow_hold':
                    $pending += $amount;
                    break;
                case 'escrow_release':
                    $pending = max(0, $pending - $amount);
                    $balance += $amount;
                    break;
                case 'platform_fee':
                    $balance -= $amount;
                    break;
                case 'refund':
                    $balance += $amount;
                    break;
            }

            $upd = $conn->prepare('UPDATE wallets SET balance = ?, pending = ?, updated_at = NOW() WHERE user_id = ?');
            $upd->bind_param('ddi', $balance, $pending, $user_id);
            $upd->execute();
            $upd->close();

            $ins = $conn->prepare(
                'INSERT INTO wallet_transactions (user_id, project_id, type, amount, balance_after, description)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );
            $ins->bind_param('iisdds', $user_id, $project_id, $type, $amount, $balance, $description);
            $ins->execute();
            $ins->close();

            $conn->commit();
            $conn->close();
            return true;

        } catch (\Throwable $e) {
            $conn->rollback();
            $conn->close();
            return false;
        }
    }
}
