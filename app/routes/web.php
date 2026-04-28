<?php
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ProfileController;
use App\Controllers\DashboardController;
/** @var \App\Core\Router $router */
$router->get('/', [HomeController::class, 'index']);
$router->get('/login',   [AuthController::class, 'showLogin']);
$router->post('/login',  [AuthController::class, 'login']);
$router->post('/signup', [AuthController::class, 'signup']);
$router->post('/logout',  [AuthController::class, 'logout']);
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/profile', [ProfileController::class, 'index']);
