<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Data;

class DashboardController extends Controller
{
    protected Data $conn;

    public function __construct()
    {
        $this->conn = new Data();
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $user = $this->conn->getUserByEmail($_SESSION['email']);

        if (!$user) {
            header("Location: /login");
            exit();
        }

        $role            = $user['user_role'];
        $is_verified     = (bool) $user['is_verified'];
        $active_projects = $this->conn->getActiveProjectsCount((int) $_SESSION['email'], $role);

        if ($role === 'Freelancer') {
            if (!$is_verified) {
                header("Location: /profile");
                exit();
            }

            if ($active_projects > 0) {
                $this->view('dashboard/freelancer/dashboard-freelancer', [
                    'active_projects' => $active_projects
                ]);
            } else {
                $this->view('dashboard/freelancer/dashboard-freelancer-empty');
            }
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