<?php
namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller
{
    private function getDb(): \mysqli
    {
        $conn = new \mysqli('db', 'appuser', 'apppass', 'freelance_marketplace');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    public function showLogin(): void
    {
        session_start();
        $errors = [
            'login'  => $_SESSION['error_login'] ?? '',
            'signup' => $_SESSION['error_signup'] ?? ''
        ];
        $active_form = $_SESSION['active_form'] ?? 'login-form';
        session_unset();

        $this->view('login/index', [
            'errors'      => $errors,
            'active_form' => $active_form
        ]);
    }

    public function login(): void
    {
        session_start();
        $conn     = $this->getDb();
        $email    = $_POST['email'];
        $password = $_POST['password'];

        $result = $conn->query("SELECT * FROM userData WHERE user_email = '$email'");
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['user_password'])) {
                $_SESSION['user_id'] = $user['user_name'];
                $_SESSION['email']   = $user['user_email'];
                $redirect = match($user['user_role']) {
                    'Client'     => '/client',
                    'Freelancer' => '/freelancer',
                    'Admin'      => '/admin',
                    'Arbitrator' => '/arbitrator',
                    default      => '/'
                };
                header("Location: $redirect");
                exit();
            }
        }
        $_SESSION['error_login'] = "Invalid email or password.";
        $_SESSION['active_form'] = "login-form";
        header("Location: /login");
        exit();
    }

    public function signup(): void
    {
        session_start();
        $conn     = $this->getDb();
        $name     = $_POST['fname'];
        $email    = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role     = $_POST['role'];

        if ($_POST['password'] !== $_POST['confirm_password']) {
            $_SESSION['error_signup'] = "Passwords do not match.";
            $_SESSION['active_form']  = "signup-form";
            header("Location: /login");
            exit();
        }

        $check = $conn->query("SELECT * FROM userData WHERE user_email = '$email'");
        if ($check->num_rows > 0) {
            $_SESSION['error_signup'] = "Email already exists.";
            $_SESSION['active_form']  = "signup-form";
        } else {
            $conn->query("INSERT INTO userData (user_email, user_password, user_name, user_role) 
                          VALUES ('$email', '$password', '$name', '$role')");
            $_SESSION['active_form'] = "login-form";
        }
        header("Location: /login");
        exit();
    }
}