<?php 
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Data;
use App\Models\Bid;
use App\Models\BidMilestone;
use App\Models\Project;

class BidController extends Controller
{   
    protected Data $conn;

    public function __construct()
    {
        $this->conn = new Data();
    }
    public function index(): void
    {
        $this->view('Bids/bid-submit');
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /bid');
            exit();
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        // Construct Bid object and populate from POST
        $bid = new Bid();
        $bid->job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : 1;
        $bid->user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 1;
        $bid->proposal_message = $_POST['cover_letter'] ?? '';
        $bid->key_differentiators = $_POST['differentiators'] ?? '';
        $bid->relevant_work = $_POST['past_work'] ?? '';
        $bid->total_bid_amount = isset($_POST['bid_total']) ? floatval($_POST['bid_total']) : 0;
        $bid->bid_rationale = $_POST['bid_rationale'] ?? '';
        $bid->start_date = $_POST['start_date'] ?? null;
        $bid->free_reviews       = intval($_POST['free_reviews'] ?? 0);
        $bid->review_price       = floatval($_POST['review_price'] ?? 0.00);
        $slots = $_POST['availability_slots'] ?? '';
        // $bid->availability_slots = is_string($slots) && trim($slots) !== '' ? $slots : '[]';

        // تأكد أن القيمة المرسلة هي مصفوفة فارغة بتنسيق JSON على الأقل
        $bid->availability_slots = json_encode($_POST['availability_slots'] ?? []);
        // Save bid
        $newBidId = $bid->save($bid);

        if (!$newBidId) {
            die('Failed to save bid.');
        }

        // Save milestones if present
        $milestonesData = $_POST['milestones'] ?? [];
        foreach ($milestonesData as $data) {
            $ms = new BidMilestone();
            $ms->bid_id = $newBidId;
            $ms->milestone_name = $data['name'] ?? 'Milestone';
            $ms->deliverables = $data['deliverables'] ?? '';
            $ms->amount = isset($data['amount']) ? floatval($data['amount']) : 0;
            $ms->duration_days = isset($data['duration']) ? intval($data['duration']) : 0;
            $ms->save($ms);
        }

        // Handle uploaded attachments (optional)
        if (!empty($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
            $uploadBase = __DIR__ . '/../../public/uploads/bids/' . $newBidId . '/';
            if (!is_dir($uploadBase)) {
                mkdir($uploadBase, 0755, true);
            }

            $fileCount = count($_FILES['attachments']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                $tmp = $_FILES['attachments']['tmp_name'][$i] ?? null;
                $name = $_FILES['attachments']['name'][$i] ?? '';
                if ($tmp && is_uploaded_file($tmp)) {
                    $safe = preg_replace('/[^A-Za-z0-9._-]/', '_', $name);
                    move_uploaded_file($tmp, $uploadBase . $safe);
                }
            }
        }

        // Redirect to dashboard (or wherever appropriate)
        header('Location: /dashboard');
        exit();
    }

    public function index2(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        $jobId = isset($_GET['job_id']) ? intval($_GET['job_id']) : 2;
        if ($jobId > 0) {
            $_SESSION['last_bid_review_job_id'] = $jobId;
        } elseif (!empty($_SESSION['last_bid_review_job_id'])) {
            $jobId = intval($_SESSION['last_bid_review_job_id']);
        }

        if ($jobId <= 0) {
            header('Location: /dashboard');
            exit();
        }

        $projectModel = new Project();
        $project = $projectModel->gitdata($jobId);
        if (!$project) {
            die('Project not found.');
        }

        $bids = $this->getBidsForProject($jobId);

        $this->view('Bids/bid-review', [
            'job' => $project,
            'bids' => $bids,
        ]);
    }

    private function getBidsForProject(int $jobId): array
    {
        $db = new Data();
        $conn = $db->getDb();

        $sql = "SELECT b.*, u.user_name AS specialist_name, SUM(bm.duration_days) AS total_duration
                FROM bids b
                JOIN userData u ON b.user_id = u.id
                LEFT JOIN bid_milestones bm ON bm.bid_id = b.id
                WHERE b.job_id = ?
                GROUP BY b.id
                ORDER BY b.created_at DESC";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param('i', $jobId);
        $stmt->execute();
        $result = $stmt->get_result();

        $bids = [];
        while ($row = $result->fetch_assoc()) {

            $row['milestones'] = json_decode($row['milestones_json'] ?? '[]', true);
            $row['availability_slots'] = json_decode($row['availability_slots'] ?? '[]', true);

            $bids[] = $row;
        }

        $stmt->close();
        $conn->close();

        return $bids;
    }

    public function store2(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /bid-review');
            exit();
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        // Construct Bid object and populate from POST
        $bid = new Bid();
    }

}