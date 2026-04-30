<?php
namespace App\Controllers;

use App\Core\Controller;


class DashboardController extends Controller
{
    private function getDb(): \mysqli
    {
        $conn = new \mysqli('db', 'appuser', 'apppass', 'freelance_marketplace');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
    public function index(): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION['email'])) {
            header("Location: /login");
            exit();
        }

        $conn = $this->getDb(); 
        $role = $conn->query("SELECT role FROM userData WHERE id = " . $_SESSION['user_id']);
        if ($role == 'Freelancer') {
            $is_verified = $conn->query("SELECT is_verified FROM userData WHERE id = " . $_SESSION['user_id']);
            $projects = $conn->query("SELECT COUNT(*) AS active_projects FROM projects WHERE freelancer_id = " . $_SESSION['user_id'] . " AND status IN ('active', 'pending')");

            if ($is_verified == 0) {
                header('/profile');
            }else if ($projects > 0) {
                $this->view('dashboard/freelancer/dashboard-freelancer', [
                    'active_projects' => $projects
                ]);
            } else {
                $this->view('dashboard/freelancer/dashboard-freelancer-empty');
            }
            $this->view('dashboard/freelancer/dashboard-freelancer');
        } 
        




        $conn = $this->getDb(); 
        $stmt = $conn->prepare("SELECT is_verified FROM userData WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($user['is_verified'] == 0) {
                $this->view('dashboard/client/dashboard-client-pending', [
                    'doc_status' => empty($user['user_SSN']) ? 'pending' : 'rejected'
                ]);
                return;
            }
        }
        $stmt->close();

        $stmt = $conn->prepare("SELECT COUNT(*) AS active_projects FROM projects WHERE client_id = ? AND status IN ('active', 'pending')");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $active_projects = $result->fetch_assoc()['active_projects'];
        if ($active_projects > 0) {
            $this->view('dashboard/client/dashboard-client', [
                'active_projects' => $active_projects
            ]);
            return;
        }
        $stmt->close();
        
        $this->view('dashboard/client/dashboard-client-empty', [
            'active_projects' => $active_projects
        ]);

    }   
}