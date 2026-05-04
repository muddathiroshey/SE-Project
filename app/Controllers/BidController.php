<?php 
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Data;
use App\Models\Bid;
use App\Models\BidMilestone;

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
}
