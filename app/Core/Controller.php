<?php

namespace App\Core;

class Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }

    protected function redirect(string $path): never
    {
        header("Location: {$path}");
        exit();
    }

    protected function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    protected function requireRole(string $role): void
    {
        $this->requireAuth();

        if (($_SESSION['role'] ?? '') !== $role) {
            $this->redirect('/dashboard');
        }
    }
}
