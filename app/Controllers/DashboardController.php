<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Data;

class DashboardController extends Controller
{
    protected Data $conn;

    public function __construct()
    {
        parent::__construct();
        $this->conn = new Data();
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $email = $_SESSION['email'] ?? null;

        if (!$email) {
            header("Location: /login");
            exit();
        }

        $user = $this->conn->getUserByEmail($email);
        if (!$user) {
            header("Location: /login");
            exit();
        }

        $role = $user['user_role'];
        $is_verified = (bool) $user['is_verified'];
        $user_id = isset($user['id']) ? (int) $user['id'] : 0;

        if ($role === 'Admin') {
            $this->view('admin/admin-dashboard');
            return;
        }

        $active_projects_count = $this->conn->getActiveProjectsCount($user_id, $role);
        $active_projects = $this->conn->getActiveProjects($user_id, $role);

        if ($role === 'Freelancer') {
            if (!$is_verified) {
                header("Location: /profile");
                exit();
            }

            if ($active_projects_count > 0) {
                $this->view('dashboard/specialist/specialist-active-projects', [
                    'active_projects_count' => $active_projects_count,
                    'projects' => $active_projects,
                    'specialist' => $user
                ]);
            } else {
                $this->view('dashboard/specialist/dashboard-specialist', [
                    'active_projects_count' => $active_projects_count,
                    'specialist' => $user
                ]);
            }
            return;
        }

        // Client
        if (!$is_verified) {
            header("Location: /profile");
            return;
        }

        if ($active_projects_count > 0) {
            $this->view('dashboard/client/dashboard-client', [
                'active_projects_count' => $active_projects_count,
                'active_projects' => $active_projects
            ]);
            return;
        }

        $this->view('dashboard/client/dashboard-client-empty', [
            'active_projects_count' => $active_projects_count,
            'active_projects' => $active_projects
        ]);
    }

    public function bids(): void 
    {
                if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $email = $_SESSION['email'] ?? null;

        if (!$email) {
            header("Location: /login");
            exit();
        }

        $user = $this->conn->getUserByEmail($email);
        if (!$user) {
            header("Location: /login");
            exit();
        }

        $role = $user['user_role'];
        $is_verified = (bool) $user['is_verified'];
        $user_id = isset($user['id']) ? (int) $user['id'] : 0;
        $active_projects = $this->conn->getActiveProjectsCount($user_id, $role);

        if ($role === 'Freelancer') {
            if (!$is_verified) {
                header("Location: /profile");
                exit();
            }
            $this->view('dashboard/specialist/my-bids');
            return;
        }
            
        // Client
        if (!$is_verified) {
            header("Location: /profile");
            return;
        }

        if ($active_projects > 0) {
            $this->view('dashboard/client/dashboard-client', [
                'active_projects' => $active_projects
            ]);
            return;
        }

        $this->view('dashboard/client/dashboard-client-empty', [
            'active_projects' => $active_projects
        ]);
    }

}


