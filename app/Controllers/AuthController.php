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
        $conn     = $this->getDb();
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        $stmt = $conn->prepare("SELECT id, user_name, user_email, user_password, user_role FROM userData WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['user_password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['email']     = $user['user_email'];
                $_SESSION['role']      = $user['user_role'];
                header("Location: /dashboard");
                exit();
            }
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
        $conn     = $this->getDb();
        $fname    = trim($_POST['fname']           ?? '');
        $lname    = trim($_POST['lname']           ?? '');
        $email    = trim($_POST['email']           ?? '');
        $raw_pass = $_POST['password']             ?? '';
        $confirm  = $_POST['confirm_password']     ?? '';
        $role     = $_POST['role']                 ?? 'Client';

        // ① Empty field check  (was using undefined $raw — fixed to $raw_pass)
        if (!$fname || !$email || !$raw_pass || !$confirm) {
            $_SESSION['error_signup'] = "All required fields must be filled in.";
            $_SESSION['active_form']  = "signup";
            header("Location: /login");
            exit();
        }

        // ② Email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_signup'] = "Please enter a valid email address.";
            $_SESSION['active_form']  = "signup";
            header("Location: /login");
            exit();
        }

        // ③ Password length
        if (strlen($raw_pass) < 10) {
            $_SESSION['error_signup'] = "Password must be at least 10 characters.";
            $_SESSION['active_form']  = "signup";
            header("Location: /login");
            exit();
        }

        // ④ Password match  (duplicate block removed)
        if ($raw_pass !== $confirm) {
            $_SESSION['error_signup'] = "Passwords do not match.";
            $_SESSION['active_form']  = "signup";
            header("Location: /login");
            exit();
        }

        $password  = password_hash($raw_pass, PASSWORD_DEFAULT);
        $full_name = trim("$fname $lname");

        // ⑤ Duplicate email check
        $check = $conn->prepare("SELECT id FROM userData WHERE user_email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $_SESSION['error_signup'] = "An account with that email already exists.";
            $_SESSION['active_form']  = "signup";
        } else {
            // ⑥ Insert
            $stmt = $conn->prepare(
                "INSERT INTO userData (user_email, user_password, user_name, user_role) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $email, $password, $full_name, $role);

            if ($stmt->execute()) {
                $_SESSION['user_id']   = $conn->insert_id;
                $_SESSION['user_name'] = $full_name;
                $_SESSION['email']     = $email;
                $_SESSION['role']      = $role;
                header("Location: /dashbord");
                exit();
            } else {
                $_SESSION['error_signup'] = "Registration failed. Please try again.";
                $_SESSION['active_form']  = "signup";
            }
        }

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
