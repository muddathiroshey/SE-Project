<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Data;

class AuthController extends Controller
{
    protected Data $conn;

    public function __construct()
    {
        $this->conn = new Data();
    }

    public function showLogin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit();
        }

        $errors = [
            'login'  => $_SESSION['error_login']  ?? '',
            'signup' => $_SESSION['error_signup'] ?? ''
        ];
        $active_form = $_SESSION['active_form'] ?? 'login';
        session_unset();

        $this->view('login/index', [
            'errors'      => $errors,
            'active_form' => $active_form
        ]);
    }

    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';

        $user = $this->conn->checkPass($password, $email);

        if ($user) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['email']     = $user['user_email'];
            $_SESSION['role']      = $user['user_role'];
            header("Location: /dashboard");
            exit();
        }

        $_SESSION['error_login'] = "Invalid email or password.";
        $_SESSION['active_form'] = "login";
        header("Location: /login");
        exit();
    }

    public function signup(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $fname    = trim($_POST['fname']            ?? '');
        $lname    = trim($_POST['lname']            ?? '');
        $email    = trim($_POST['email']            ?? '');
        $raw_pass = $_POST['password']              ?? '';
        $confirm  = $_POST['confirm_password']      ?? '';
        $role     = $_POST['role']                  ?? 'Client';

        // Validation
        if (!$fname || !$email || !$raw_pass || !$confirm) {
            $this->signupError("All required fields must be filled in.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->signupError("Please enter a valid email address.");
        }

        if (strlen($raw_pass) < 10) {
            $this->signupError("Password must be at least 10 characters.");
        }

        if ($raw_pass !== $confirm) {
            $this->signupError("Passwords do not match.");
        }

        if ($this->conn->checkEmail($email)) {
            $this->signupError("An account with that email already exists.");
        }

        // Insert
        $full_name = trim("$fname $lname");
        $new_id    = $this->conn->AddUser($email, $raw_pass, $full_name, $role);

        if ($new_id) {
            $_SESSION['user_id']   = $new_id;
            $_SESSION['user_name'] = $full_name;
            $_SESSION['email']     = $email;
            $_SESSION['role']      = $role;
            header("Location: /dashboard");
            exit();
        }

        $this->signupError("Registration failed. Please try again.");
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: /");
        exit();
    }

    // Helper to avoid repeating the 3-line error pattern
    private function signupError(string $message): never
    {
        $_SESSION['error_signup'] = $message;
        $_SESSION['active_form']  = 'signup';
        header("Location: /login");
        exit();
    }
}