<?php

namespace App\Core;

class App
{
    public function run(): void
    {
        $router = new Router();
        require __DIR__ . '/../routes/web.php';
        $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    }
}
