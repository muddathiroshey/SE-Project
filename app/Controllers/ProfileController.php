<?php
namespace App\Controllers;

use App\Core\Controller;

class ProfileController extends Controller
{
    public function index(): void
    {
        
        if (!isset($_SESSION)) {
            session_start();
        }
        
        
        if (!isset($_SESSION['email'])) {
            header("Location: /login");
            exit();
        }
        
        $view = match($_SESSION['role'] ?? 'Client') {
            'Freelancer' => 'profile/freelancer/index',
            'Admin'      => 'profile/admin/index',
            'Arbitrator' => 'profile/arbitrator/index',
            default      => 'profile/client/index'
        };

        $this->view($view, [
            'title' => ($_SESSION['user_name'] ?? 'User') . "'s Profile"
        ]);
        
    }
}