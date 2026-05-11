<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Client;
use App\Models\Data;
use App\Models\Project;


class  ProjectController extends Controller
{   
    protected Data $conn;

    public function __construct()
    {
        parent::__construct();
        $this->conn = new Data();
    }

    public function postJob(): void
    {
        $this->requireRole('Client');
        $this->view('job/post-job');
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/post-job');
        }

        $this->requireRole('Client');

        $errors = $this->validateProjectPost($_POST, $_FILES);
        if ($errors) {
            $this->view('job/post-job', ['errors' => $errors, 'old' => $_POST]);
            return;
        }

        $clientModel = new Client();
        $client = $clientModel->getByUserId((int) $_SESSION['user_id']);
        if (!$client) {
            $this->redirect('/profile/setup');
        }

        $project = new Project();
        $project->user_id = (int) $_SESSION['user_id'];
        $project->client_id = (int) $client['id'];
        $project->niche = trim($_POST['niche'] ?? '');
        $project->niche_answers_json = $this->jsonPayload($_POST['niche_answers_json'] ?? '', $_POST['niche_answers'] ?? []);
        $project->project_title = trim($_POST['project_title'] ?? '');
        $project->project_brief = trim($_POST['project_brief'] ?? '');
        $project->project_full_requirements = trim($_POST['project_full_requirements'] ?? '');
        $project->ideal_candidate = trim($_POST['ideal_candidate'] ?? '');
        $project->milestones_json = $this->milestonesPayload($_POST['milestones'] ?? [], $_POST['milestones_json'] ?? '');
        $project->total_budget = $this->milestoneTotal($_POST['milestones'] ?? []);
        $project->platform_fee = round($project->total_budget * 0.065, 2);
        $project->specialist_receives = round($project->total_budget - $project->platform_fee, 2);
        $project->first_escrow_required = $this->firstMilestoneAmount($_POST['milestones'] ?? []);
        $project->free_revisions = isset($_POST['free_revisions']) ? 1 : 0;
        $project->nda_type = $_POST['nda_type'] ?? 'standard';
        $project->nda_duration = trim($_POST['nda_duration'] ?? '');
        $project->nda_damages = trim($_POST['nda_damages'] ?? '');
        $project->nda_custom_amount = isset($_POST['nda_custom_amount']) ? (int) $_POST['nda_custom_amount'] : 0;
        $project->nda_file_path = null;
        $project->profile_masking = isset($_POST['profile_masking']) ? intval($_POST['profile_masking']) : 0;
        $project->visibility = $_POST['visibility'] ?? 'public';

        if ($project->nda_type === 'custom' && !empty($_FILES['nda_file'])) {
            $uploadBase = __DIR__ . '/../../public/uploads/ndas/';
            if (!is_dir($uploadBase)) {
                mkdir($uploadBase, 0755, true);
            }
            $tmp = $_FILES['nda_file']['tmp_name'] ?? null;
            $name = $_FILES['nda_file']['name'] ?? '';
            if ($tmp && is_uploaded_file($tmp)) {
                $safe = preg_replace('/[^A-Za-z0-9._-]/', '_', $name);
                $filename = time() . '_' . $safe;
                move_uploaded_file($tmp, $uploadBase . $filename);
                $project->nda_file_path = 'uploads/ndas/' . $filename;
            }
        }

        $projectId = $project->save($project);

        if (!$projectId) {
            die('Failed to save project.');
        }

        $this->redirect('/dashboard');
    }

    public function Jobview(): void
    {
        $this->requireAuth();
        $this->view('job/job-view');
    }

     public function ProjectDetail(): void
    {
        $this->requireAuth();
        $this->view('job/project-detail');
    }

     public function ProjectDetailInDispute(): void
    {
        $this->requireAuth();
        $this->view('job/project-detail(in-dispute)');
    }

    private function validateProjectPost(array $post, array $files): array
    {
        $errors = [];

        foreach (['niche' => 'Project niche', 'project_title' => 'Project title', 'project_brief' => 'Project brief', 'project_full_requirements' => 'Full requirements', 'ideal_candidate' => 'Ideal candidate'] as $field => $label) {
            if (trim($post[$field] ?? '') === '') {
                $errors[] = "{$label} is required.";
            }
        }

        if ($this->milestoneTotal($post['milestones'] ?? []) <= 0) {
            $errors[] = 'At least one milestone with a positive amount is required.';
        }

        if (($post['nda_type'] ?? 'standard') === 'custom') {
            $upload = $files['nda_file'] ?? null;
            if (!$upload || ($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $errors[] = 'Upload your custom NDA file.';
            }
        }

        if (!isset($post['agree_terms'])) {
            $errors[] = 'You must agree to the posting terms.';
        }

        return $errors;
    }

    private function jsonPayload(string $json, array $fallback): string
    {
        if ($json !== '' && json_decode($json, true) !== null) {
            return $json;
        }

        return json_encode($fallback, JSON_UNESCAPED_UNICODE) ?: '[]';
    }

    
    private function milestonesPayload(array $milestones, string $json): string
    {
        $clean = [];
        foreach ($milestones as $index => $milestone) {
            $amount = (float) ($milestone['amount'] ?? 0);
            $name = trim($milestone['name'] ?? '');
            if ($name === '' && $amount <= 0) {
                continue;
            }

            $clean[] = [
                'index' => $index,
                'name' => $name !== '' ? $name : 'Milestone',
                'duration_days' => (int) ($milestone['duration_days'] ?? $milestone['duration'] ?? 0),
                'amount' => $amount,
            ];
        }

        if ($clean) {
            return json_encode($clean, JSON_UNESCAPED_UNICODE) ?: '[]';
        }

        return $this->jsonPayload($json, []);
    }

    private function milestoneTotal(array $milestones): float
    {
        return array_reduce($milestones, fn ($total, $milestone) => $total + (float) ($milestone['amount'] ?? 0), 0.0);
    }

    private function firstMilestoneAmount(array $milestones): float
    {
        $first = reset($milestones);
        return is_array($first) ? (float) ($first['amount'] ?? 0) : 0.0;
    }

}
