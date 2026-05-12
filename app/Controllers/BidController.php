<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Bid;
use App\Models\BidMilestone;
use App\Models\Data;
use App\Models\Project;
use DateTime;

class BidController extends Controller
{
    protected Data $conn;

    public function __construct()
    {
        parent::__construct();
        $this->conn = new Data();
    }

    // ─────────────────────────────────────────────────────────────
    // SPECIALIST: Show bid submission form  GET /jobs/{id}/bid
    //             or edit form              GET /jobs/{id}/bid/{bid_id}
    // ─────────────────────────────────────────────────────────────
    public function index(): void
    {
        $this->requireRole('Freelancer');

        $jobId  = (int) ($_GET['job_id'] ?? 0);
        $bidId  = (int) ($_GET['bid_id'] ?? 0);

        // Resolve job record
        $projectModel = new Project();
        $job = $jobId > 0 ? $projectModel->gitdata($jobId) : null;
        if (!$job) {
            // Fall back to the first project for demo purposes
            $job = $projectModel->gitdata(1) ?? [];
        }

        $jobId = (int) ($job['id'] ?? 0);

        // Load existing bid (edit flow) or null (new flow)
        $bid = null;
        if ($bidId > 0) {
            $bid = $this->getBidById($bidId);
            if ($bid && (int) $bid['user_id'] !== (int) $_SESSION['user_id']) {
                $this->redirect('/dashboard'); // not their bid
            }
        }

        // Withdrawal window
        $canWithdraw      = false;
        $withdrawDeadline = null;
        $hoursRemaining   = 0;
        if ($bid && !empty($bid['submitted_at'])) {
            $submittedAt      = new DateTime($bid['submitted_at']);
            $withdrawDeadline = (clone $submittedAt)->modify('+48 hours');
            $now              = new DateTime();
            if ($now < $withdrawDeadline) {
                $canWithdraw    = true;
                $diff           = $now->diff($withdrawDeadline);
                $hoursRemaining = ($diff->days * 24) + $diff->h;
            }
        }

        // Client info for the job
        $client = $this->getClientForJob($jobId);

        // Job's proposed milestones (for comparison in submit form)
        $milestones = $this->getJobMilestones($jobId);

        // Specialist profile for fee rate
        $specialist = $this->getSpecialistProfile((int) $_SESSION['user_id']);

        // Match score (if already bid; else compute fresh)
        $matchScore = $bid ? (int) ($bid['match_score'] ?? 0) : 0;

        // Competition context (aggregated, never individual bids)
        $bidContext = $this->getBidContext($jobId);
        $job['bid_low']    = $bidContext['low'] ?? 0;
        $job['bid_median'] = $bidContext['median'] ?? 0;
        $job['bid_high']   = $bidContext['high'] ?? 0;
        $job['bid_count']  = $bidContext['count'] ?? 0;

        $this->view('Bids/bid-submit', [
            'job'              => $job,
            'client'           => $client,
            'milestones'       => $milestones,
            'bid'              => $bid,
            'canWithdraw'      => $canWithdraw,
            'withdrawDeadline' => $withdrawDeadline,
            'hoursRemaining'   => $hoursRemaining,
            'specialist'       => $specialist,
            'matchScore'       => $matchScore,
            'errors'           => [],
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // SPECIALIST: Submit new bid  POST /jobs/{id}/bid
    //             Update bid      PATCH /jobs/{id}/bid/{bid_id}
    // ─────────────────────────────────────────────────────────────
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bid');
        }

        $this->requireRole('Freelancer');

        $errors = $this->validateBid($_POST);

        if ($errors) {
            // Re-render with errors + old input
            $jobId      = (int) ($_POST['job_id'] ?? 0);
            $projectModel = new Project();
            $job        = $jobId > 0 ? $projectModel->gitdata($jobId) : [];
            $milestones = $this->getJobMilestones($jobId);
            $client     = $this->getClientForJob($jobId);
            $specialist = $this->getSpecialistProfile((int) $_SESSION['user_id']);
            $bidContext = $this->getBidContext($jobId);
            if ($job) {
                $job['bid_low']    = $bidContext['low'] ?? 0;
                $job['bid_median'] = $bidContext['median'] ?? 0;
                $job['bid_high']   = $bidContext['high'] ?? 0;
                $job['bid_count']  = $bidContext['count'] ?? 0;
            }

            $this->view('Bids/bid-submit', [
                'job'              => $job ?? [],
                'client'           => $client,
                'milestones'       => $milestones,
                'bid'              => null,
                'canWithdraw'      => false,
                'withdrawDeadline' => null,
                'hoursRemaining'   => 0,
                'specialist'       => $specialist,
                'matchScore'       => 0,
                'errors'           => $errors,
                'old'              => $_POST,
            ]);
            return;
        }

        // Build and save bid
        $bid                    = new Bid();
        $bid->job_id            = (int) ($_POST['job_id'] ?? 0);
        $bid->user_id           = (int) $_SESSION['user_id'];
        $bid->proposal_message  = trim($_POST['cover_letter'] ?? '');
        $bid->key_differentiators = trim($_POST['differentiators'] ?? '');
        $bid->relevant_work     = trim($_POST['past_work'] ?? '');
        $bid->total_bid_amount  = (float) ($_POST['bid_total'] ?? 0);
        $bid->bid_rationale     = trim($_POST['bid_rationale'] ?? '');
        $bid->start_date        = trim($_POST['start_date'] ?? '') ?: null;
        $bid->free_reviews      = (int) ($_POST['free_reviews'] ?? 0);
        $bid->review_price      = (float) ($_POST['review_price'] ?? 0);
        $bid->availability_slots = $this->availabilityPayload($_POST['availability_slots'] ?? []);
        $bid->submitted_at      = date('Y-m-d H:i:s');

        $newBidId = $bid->save($bid);
        if (!$newBidId) {
            die('Failed to save bid.');
        }

        // Save milestones
        foreach ($_POST['milestones'] ?? [] as $data) {
            $amount = (float) ($data['amount'] ?? 0);
            if ($amount <= 0 && trim($data['name'] ?? '') === '') {
                continue;
            }
            $ms               = new BidMilestone();
            $ms->bid_id       = $newBidId;
            $ms->milestone_name = trim($data['name'] ?? '') ?: 'Milestone';
            $ms->deliverables = trim($data['deliverables'] ?? '');
            $ms->amount       = $amount;
            $ms->duration_days = (int) ($data['duration_days'] ?? $data['duration'] ?? 0);
            $ms->save($ms);
        }

        $this->storeAttachments($newBidId, $bid);

        $this->redirect('/dashboard');
    }

    // ─────────────────────────────────────────────────────────────
    // CLIENT: Review proposals  GET /bid-review?job_id={id}
    // ─────────────────────────────────────────────────────────────
    public function index2(): void
    {
        $this->requireRole('Client');

        $jobId = (int) ($_GET['job_id'] ?? ($_SESSION['last_bid_review_job_id'] ?? 0));
        if ($jobId <= 0) {
            $this->redirect('/dashboard');
        }

        $_SESSION['last_bid_review_job_id'] = $jobId;

        $projectModel = new Project();
        $job = $projectModel->gitdata($jobId);
        if (!$job) {
            die('Project not found.');
        }

        $bids = $this->getBidsForProject($jobId);

        // Determine active bid (from ?bid= param or first)
        $activeBidId = (int) ($_GET['bid'] ?? 0);
        $activeBid   = null;
        if ($activeBidId > 0) {
            foreach ($bids as $b) {
                if ((int) $b['id'] === $activeBidId) { $activeBid = $b; break; }
            }
        }
        $activeBid = $activeBid ?? ($bids[0] ?? null);

        // Availability slots for interview modal (from active bid)
        $interviewSlots = [];
        if ($activeBid) {
            $slots = $activeBid['availability_slots'] ?? [];
            $interviewSlots = is_array($slots) ? $slots : (json_decode($slots, true) ?: []);
        }

        // Can the client accept a bid? (no accepted contract on this job yet)
        $canAccept = !$this->hasAcceptedBid($jobId);

        $this->view('Bids/bid-review', [
            'job'            => $job,
            'bids'           => $bids,
            'activeBid'      => $activeBid,
            'bidCount'       => count($bids),
            'client'         => $_SESSION,
            'canAccept'      => $canAccept,
            'interviewSlots' => $interviewSlots,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // CLIENT: Accept / decline bid  POST /bid-review
    // ─────────────────────────────────────────────────────────────
    public function store2(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bid-review');
        }

        $this->requireRole('Client');

        $bidId  = (int) ($_POST['bid_id'] ?? 0);
        $jobId  = (int) ($_POST['job_id'] ?? ($_SESSION['last_bid_review_job_id'] ?? 0));
        $action = $_POST['action'] ?? '';

        if (!$bidId || !in_array($action, ['accept', 'decline', 'shortlist'], true)) {
            $this->redirect('/bid-review' . ($jobId ? '?job_id=' . $jobId : ''));
        }

        $status = match ($action) {
            'accept'    => 'accepted',
            'decline'   => 'rejected',
            'shortlist' => 'shortlisted',
        };

        $bid = new Bid();
        $bid->updateStatus($bidId, $status);

        // If accepting, automatically decline all other bids on this job
        if ($action === 'accept') {
            $this->declineOtherBids($jobId, $bidId);
        }

        $this->redirect('/bid-review' . ($jobId ? '?job_id=' . $jobId : ''));
    }

    // ─────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────

    private function getBidById(int $bidId): ?array
    {
        $db   = new Data();
        $conn = $db->getDb();

        $sql = "SELECT b.*, u.user_name AS specialist_name,
                       COALESCE(ms.total_duration, 0) AS total_duration
                FROM bids b
                JOIN userData u ON b.user_id = u.id
                LEFT JOIN (
                    SELECT bid_id, SUM(duration_days) AS total_duration
                    FROM bid_milestones
                    GROUP BY bid_id
                ) ms ON ms.bid_id = b.id
                WHERE b.id = ?
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param('i', $bidId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();

        if ($row) {
            $row['availability_slots'] = json_decode($row['availability_slots'] ?? '[]', true) ?: [];
            $row['milestones']         = $this->getMilestonesForBid($bidId);
            $row['attachments']        = $this->getAttachmentsForBid($bidId);
        }

        return $row ?: null;
    }

    private function getBidsForProject(int $jobId): array
    {
        $db   = new Data();
        $conn = $db->getDb();

        $sql = "SELECT b.*, b.submitted_at AS created_at, u.user_name AS specialist_name,
                       COALESCE(ms.total_duration, 0) AS total_duration
                FROM bids b
                JOIN userData u ON b.user_id = u.id
                LEFT JOIN (
                    SELECT bid_id, SUM(duration_days) AS total_duration
                    FROM bid_milestones
                    GROUP BY bid_id
                ) ms ON ms.bid_id = b.id
                WHERE b.job_id = ?
                ORDER BY b.submitted_at DESC";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param('i', $jobId);
        $stmt->execute();
        $result = $stmt->get_result();

        $bids = [];
        while ($row = $result->fetch_assoc()) {
            $row['availability_slots'] = json_decode($row['availability_slots'] ?? '[]', true) ?: [];
            $row['milestones']         = $this->getMilestonesForBid((int) $row['id']);
            $row['attachments']        = $this->getAttachmentsForBid((int) $row['id']);
            $bids[] = $row;
        }

        $stmt->close();
        $conn->close();

        return $bids;
    }

    private function getMilestonesForBid(int $bidId): array
    {
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare('SELECT * FROM bid_milestones WHERE bid_id = ? ORDER BY sort_order, id');
        if (!$stmt) return [];
        $stmt->bind_param('i', $bidId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    private function getAttachmentsForBid(int $bidId): array
    {
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare('SELECT * FROM bid_attachments WHERE bid_id = ? ORDER BY id');
        if (!$stmt) return [];
        $stmt->bind_param('i', $bidId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    private function getJobMilestones(int $jobId): array
    {
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare(
            'SELECT * FROM project_milestones WHERE project_id = ? ORDER BY sort_order, id'
        );
        if (!$stmt) return [];
        $stmt->bind_param('i', $jobId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $rows;
    }

    private function getClientForJob(int $jobId): array
    {
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare(
            'SELECT u.*, p.org_name, p.verified,
                    p.payment_reliability, p.dispute_rate, p.completed_projects
             FROM projects j
             JOIN userData u ON j.client_id = u.id
             LEFT JOIN client_profiles p ON p.user_id = u.id
             WHERE j.id = ?
             LIMIT 1'
        );
        if (!$stmt) return [];
        $stmt->bind_param('i', $jobId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?? [];
        $stmt->close();
        $conn->close();
        return $row;
    }

    private function getSpecialistProfile(int $userId): array
    {
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare(
            'SELECT u.*, sp.fee_rate, sp.rating, sp.completed_projects,
                    sp.milestone_rate, sp.total_delivered, sp.location,
                    sp.specialist_title, sp.verified
             FROM userData u
             LEFT JOIN specialist_profiles sp ON sp.user_id = u.id
             WHERE u.id = ?
             LIMIT 1'
        );
        if (!$stmt) return ['fee_rate' => 0.065];
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?? [];
        $stmt->close();
        $conn->close();
        $row['fee_rate'] = (float) ($row['fee_rate'] ?? 0.065);
        return $row;
    }

    /**
     * Return anonymous aggregated bid stats for competition context widget.
     * Never returns individual bids — only aggregate values.
     */
    private function getBidContext(int $jobId): array
    {
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare(
            'SELECT
                MIN(total_bid_amount) AS low,
                MAX(total_bid_amount) AS high,
                AVG(total_bid_amount) AS median,
                COUNT(*)             AS count
             FROM bids
             WHERE job_id = ? AND status NOT IN (\'rejected\', \'withdrawn\')'
        );
        if (!$stmt) return [];
        $stmt->bind_param('i', $jobId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?? [];
        $stmt->close();
        $conn->close();
        return [
            'low'    => (float) ($row['low']    ?? 0),
            'median' => (float) ($row['median'] ?? 0),
            'high'   => (float) ($row['high']   ?? 0),
            'count'  => (int)   ($row['count']  ?? 0),
        ];
    }

    private function hasAcceptedBid(int $jobId): bool
    {
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare(
            'SELECT id FROM bids WHERE job_id = ? AND status = \'accepted\' LIMIT 1'
        );
        if (!$stmt) return false;
        $stmt->bind_param('i', $jobId);
        $stmt->execute();
        $found = (bool) $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $found;
    }

    private function declineOtherBids(int $jobId, int $acceptedBidId): void
    {
        $db   = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare(
            'UPDATE bids SET status = \'rejected\'
             WHERE job_id = ? AND id != ? AND status NOT IN (\'accepted\', \'rejected\')'
        );
        if (!$stmt) return;
        $stmt->bind_param('ii', $jobId, $acceptedBidId);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    private function storeAttachments(int $bidId, Bid $bid): void
    {
        if (empty($_FILES['attachments']) || empty($_FILES['attachments']['name'][0])) {
            return;
        }

        $uploadBase = __DIR__ . '/../../public/uploads/bids/' . $bidId . '/';
        if (!is_dir($uploadBase)) {
            mkdir($uploadBase, 0755, true);
        }

        $fileCount = count($_FILES['attachments']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            $tmp  = $_FILES['attachments']['tmp_name'][$i] ?? null;
            $name = $_FILES['attachments']['name'][$i] ?? '';
            if (!$tmp || !is_uploaded_file($tmp)) {
                continue;
            }
            $safe = preg_replace('/[^A-Za-z0-9._-]/', '_', $name);
            if (move_uploaded_file($tmp, $uploadBase . $safe)) {
                $bid->addAttachment($bidId, [
                    'file_path' => 'uploads/bids/' . $bidId . '/' . $safe,
                    'file_name' => $name,
                    'mime_type' => $_FILES['attachments']['type'][$i] ?? null,
                    'file_size' => (int) ($_FILES['attachments']['size'][$i] ?? 0),
                ]);
            }
        }
    }

    public function validateBid(array $post): array
    {
        $errors = [];

        if (strlen(trim($post['cover_letter'] ?? '')) < 100) {
            $errors[] = 'Proposal message must be at least 100 characters.';
        }

        if ((float) ($post['bid_total'] ?? 0) < 500) {
            $errors[] = 'Bid amount must be at least $500.';
        }

        // Milestone total must match bid total
        $msTotal = array_sum(array_column($post['milestones'] ?? [], 'amount'));
        $bidTotal = (float) ($post['bid_total'] ?? 0);
        if ($msTotal > 0 && abs($msTotal - $bidTotal) > 0.01) {
            $errors[] = 'Milestone total must equal your bid amount.';
        }

        if (empty($post['agree_accurate']) || empty($post['agree_qualified']) || empty($post['agree_terms'])) {
            $errors[] = 'Confirm the proposal declarations before submitting.';
        }

        return $errors;
    }

    private function availabilityPayload(array|string $slots): string
    {
        if (is_array($slots)) {
            return json_encode(array_values(array_filter($slots)), JSON_UNESCAPED_UNICODE) ?: '[]';
        }

        $slots = trim($slots);
        if ($slots === '') return '[]';

        $decoded = json_decode($slots, true);
        if (is_array($decoded)) {
            return json_encode($decoded, JSON_UNESCAPED_UNICODE) ?: '[]';
        }

        return json_encode(array_map('trim', explode(',', $slots)), JSON_UNESCAPED_UNICODE) ?: '[]';
    }
}
