<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Bid;
use App\Models\BidMilestone;
use App\Models\Data;
use App\Models\Project;

class BidController extends Controller
{
    protected Data $conn;

    public function __construct()
    {
        parent::__construct();
        $this->conn = new Data();
    }

    public function index(): void
    {
        $this->requireRole('Freelancer');
        $this->view('Bids/bid-submit');
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bid');
        }

        $this->requireRole('Freelancer');

        $errors = $this->validateBid($_POST);
        if ($errors) {
            $this->view('Bids/bid-submit', ['errors' => $errors, 'old' => $_POST]);
            return;
        }

        $bid = new Bid();
        $bid->job_id = (int) ($_POST['job_id'] ?? 1);
        $bid->user_id = (int) $_SESSION['user_id'];
        $bid->proposal_message = trim($_POST['cover_letter'] ?? '');
        $bid->key_differentiators = trim($_POST['differentiators'] ?? '');
        $bid->relevant_work = trim($_POST['past_work'] ?? '');
        $bid->total_bid_amount = (float) ($_POST['bid_total'] ?? 0);
        $bid->bid_rationale = trim($_POST['bid_rationale'] ?? '');
        $bid->start_date = trim($_POST['start_date'] ?? '') ?: null;
        $bid->free_reviews = (int) ($_POST['free_reviews'] ?? 0);
        $bid->review_price = (float) ($_POST['review_price'] ?? 0);
        $bid->availability_slots = $this->availabilityPayload($_POST['availability_slots'] ?? []);

        $newBidId = $bid->save($bid);
        if (!$newBidId) {
            die('Failed to save bid.');
        }

        foreach ($_POST['milestones'] ?? [] as $data) {
            $amount = (float) ($data['amount'] ?? 0);
            if ($amount <= 0 && trim($data['name'] ?? '') === '') {
                continue;
            }

            $ms = new BidMilestone();
            $ms->bid_id = $newBidId;
            $ms->milestone_name = trim($data['name'] ?? '') ?: 'Milestone';
            $ms->deliverables = trim($data['deliverables'] ?? '');
            $ms->amount = $amount;
            $ms->duration_days = (int) ($data['duration_days'] ?? $data['duration'] ?? 0);
            $ms->save($ms);
        }

        $this->storeAttachments($newBidId, $bid);

        $this->redirect('/dashboard');
    }

    public function index2(): void
    {
        $this->requireRole('Client');

        $jobId = (int) ($_GET['job_id'] ?? ($_SESSION['last_bid_review_job_id'] ?? 2));
        if ($jobId <= 0) {
            $this->redirect('/dashboard');
        }

        $_SESSION['last_bid_review_job_id'] = $jobId;

        $projectModel = new Project();
        $project = $projectModel->gitdata($jobId);
        if (!$project) {
            die('Project not found.');
        }

        $this->view('Bids/bid-review', [
            'job' => $project,
            'bids' => $this->getBidsForProject($jobId),
        ]);
    }

    public function store2(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bid-review');
        }

        $this->requireRole('Client');

        $bidId = (int) ($_POST['bid_id'] ?? 0);
        $jobId = (int) ($_POST['job_id'] ?? ($_SESSION['last_bid_review_job_id'] ?? 0));
        $action = $_POST['action'] ?? '';

        if (!$bidId || !in_array($action, ['accept', 'decline', 'shortlist'], true)) {
            $this->redirect('/bid-review' . ($jobId ? '?job_id=' . $jobId : ''));
        }

        $status = match ($action) {
            'accept' => 'accepted',
            'decline' => 'rejected',
            default => 'shortlisted',
        };

        $bid = new Bid();
        $bid->updateStatus($bidId, $status);

        $this->redirect('/bid-review' . ($jobId ? '?job_id=' . $jobId : ''));
    }

    private function getBidsForProject(int $jobId): array
    {
        $db = new Data();
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
        if (!$stmt) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param('i', $jobId);
        $stmt->execute();
        $result = $stmt->get_result();

        $bids = [];
        while ($row = $result->fetch_assoc()) {
            $row['availability_slots'] = json_decode($row['availability_slots'] ?? '[]', true) ?: [];
            $row['milestones'] = $this->getMilestonesForBid((int) $row['id']);
            $bids[] = $row;
        }

        $stmt->close();
        $conn->close();

        return $bids;
    }

    private function getMilestonesForBid(int $bidId): array
    {
        $db = new Data();
        $conn = $db->getDb();
        $stmt = $conn->prepare('SELECT * FROM bid_milestones WHERE bid_id = ? ORDER BY sort_order, id');
        $stmt->bind_param('i', $bidId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();

        return $rows;
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
            $tmp = $_FILES['attachments']['tmp_name'][$i] ?? null;
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

    private function validateBid(array $post): array
    {
        $errors = [];

        if (strlen(trim($post['cover_letter'] ?? '')) < 100) {
            $errors[] = 'Proposal message must be at least 100 characters.';
        }

        if ((float) ($post['bid_total'] ?? 0) < 500) {
            $errors[] = 'Bid amount must be at least $500.';
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
        if ($slots === '') {
            return '[]';
        }

        $decoded = json_decode($slots, true);
        if (is_array($decoded)) {
            return json_encode($decoded, JSON_UNESCAPED_UNICODE) ?: '[]';
        }

        return json_encode(array_map('trim', explode(',', $slots)), JSON_UNESCAPED_UNICODE) ?: '[]';
    }
}
