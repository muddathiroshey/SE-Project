<?php
namespace App\Controllers;
use App\Core\Controller;

class AuthController extends Controller
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new UserModel($db);
    }

    public function login(): void
    {
        session_start();

        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['user_password'])) {

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
        session_start();

        $fname    = trim($_POST['fname'] ?? '');
        $lname    = trim($_POST['lname'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $role     = $_POST['role'] ?? 'Client';

        // validation (ممكن تخليها Service لو عايز احتراف أكتر)
        if (!$fname || !$email || !$password || !$confirm) {
            $_SESSION['error_signup'] = "All required fields must be filled in.";
            $_SESSION['active_form']  = "signup";
            header("Location: /login");
            exit();
        }

        if ($password !== $confirm) {
            $_SESSION['error_signup'] = "Passwords do not match.";
            $_SESSION['active_form']  = "signup";
            header("Location: /login");
            exit();
        }

        $created = $this->userModel->createUser($fname, $lname, $email, $password, $role);

        if ($created) {
            header("Location: /login");
            exit();
        }

        $_SESSION['error_signup'] = "Signup failed.";
        header("Location: /login");
        exit();
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

}
?>