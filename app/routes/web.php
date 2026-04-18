<?php
use App\Controllers\AuthController;
use App\Controllers\HomeController;

/** @var \App\Core\Router $router */
$router->get('/', [HomeController::class, 'index']);
$router->get('/login',   [AuthController::class, 'showLogin']);
$router->post('/login',  [AuthController::class, 'login']);
$router->post('/signup', [AuthController::class, 'signup']);