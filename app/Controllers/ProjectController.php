<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Data;
use App\Models\Project;


class  ProjectController extends Controller
{   
    protected Data $conn;

    public function __construct()
    {
        $this->conn = new Data();
    }
    public function postJob(): void
    {
        $this->view('job/post-job');
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /post-job');
            exit();
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        $email = $_SESSION['email'] ?? null;
        if (!$email) {
            header('Location: /login');
            exit();
        }

        // Create and populate project object
        $project = new Project();
        $project->user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
        $project->client_id = $project->user_id;
        $project->niche = $_POST['niche'] ?? '';
        $project->niche_answers_json = $_POST['niche_answers_json'] ?? '[]';
        $project->project_title = $_POST['project_title'] ?? '';
        $project->project_brief = $_POST['project_brief'] ?? '';
        $project->project_full_requirements = $_POST['project_full_requirements'] ?? '';
        $project->ideal_candidate = $_POST['ideal_candidate'] ?? '';
        $project->milestones_json = $_POST['milestones_json'] ?? '[]';
        $project->total_budget = isset($_POST['total_budget']) ? floatval($_POST['total_budget']) : 0;
        $project->platform_fee = isset($_POST['platform_fee']) ? floatval($_POST['platform_fee']) : 0;
        $project->specialist_receives = isset($_POST['specialist_receives']) ? floatval($_POST['specialist_receives']) : 0;
        $project->first_escrow_required = isset($_POST['first_escrow_required']) ? floatval($_POST['first_escrow_required']) : 0;
        $project->free_revisions = isset($_POST['free_revisions']) ? intval($_POST['free_revisions']) : 0;
        $project->nda_type = $_POST['nda_type'] ?? 'standard';
        $project->nda_duration = $_POST['nda_duration'] ?? '';
        $project->nda_damages = $_POST['nda_damages'] ?? '';
        $project->nda_custom_amount = isset($_POST['nda_custom_amount']) ? intval($_POST['nda_custom_amount']) : 0;
        $project->nda_file_path = null;
        $project->profile_masking = isset($_POST['profile_masking']) ? intval($_POST['profile_masking']) : 0;
        $project->visibility = $_POST['nda_visibility'] ?? 'public';

        // Handle NDA file upload if custom NDA selected
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

        // Save project
        $projectId = $project->save($project);

        if (!$projectId) {
            die('Failed to save project.');
        }

        // Redirect to dashboard
        header('Location: /dashboard');
        exit();
    }

    public function Jobview(): void
    {
        $this->view('job/job-view');
    }

     public function ProjectDetail(): void
    {
        $this->view('job/project-detail');
    }

     public function ProjectDetailInDispute(): void
    {
        $this->view('job/project-detail(in-dispute)');
    }

}