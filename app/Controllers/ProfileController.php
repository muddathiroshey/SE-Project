<?php
namespace App\Controllers;
use App\Controllers\AuthController;
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
        
      $this->view('/profile/freelancer/index');

        
    }
}