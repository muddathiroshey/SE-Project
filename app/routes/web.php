<?php
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ProfileController;
use App\Controllers\DashboardController;
use App\Controllers\BidController;

/** @var \App\Core\Router $router */

//  Public
$router->get('/',        [HomeController::class,  'index']);
$router->get('/login',   [AuthController::class,  'showLogin']);
$router->post('/login',  [AuthController::class,  'login']);
$router->post('/signup', [AuthController::class,  'signup']);
$router->post('/logout', [AuthController::class,  'logout']);

//  Dashboard 
$router->get('/dashboard', [DashboardController::class, 'index']);

//Profile 
$router->get('/profile',          [ProfileController::class, 'index']);
$router->get('/profile/setup',    [ProfileController::class, 'setup']);
$router->post('/profile/setup',   [ProfileController::class, 'store']);
$router->get('/profile/edit',     [ProfileController::class, 'edit']);
$router->post('/profile/update',  [ProfileController::class, 'update']);
$router->post('/profile/kyc/delete', [ProfileController::class, 'deleteKycDoc']);

// Bid 
$router->get('/bid', [BidController::class, 'index']);