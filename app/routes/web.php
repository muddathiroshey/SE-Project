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
$router->get('/dashboard/my-bids', [DashboardController::class, 'bids']);
//Profile 
$router->get('/profile',          [ProfileController::class, 'index']);
$router->get('/profile/setup',    [ProfileController::class, 'setup']);
$router->post('/profile/setup',   [ProfileController::class, 'store']);
$router->get('/profile/edit',     [ProfileController::class, 'edit']);
$router->post('/profile/update',  [ProfileController::class, 'update']);
$router->post('/profile/kyc/delete', [ProfileController::class, 'deleteKycDoc']);
// Fatal error: Uncaught Error: Class "AuthController" not found in /var/www/html/app/Core/Router.php:31 Stack trace: #0 /var/www/html/app/Core/App.php(11): App\Core\Router->dispatch('/login', 'GET') #1 /var/www/html/public/index.php(8): App\Core\App->run() #2 {main} thrown in /var/www/html/app/Core/Router.php on line 31
// Bid 
$router->get('/bid',              [BidController::class, 'index']);
// Accept POST submissions from the bid form
$router->post('/bid',             [BidController::class, 'store']);
//$router->get('/bid', [\App\Controllers\BidController::class, 'index']);
// عند طلب الرابط /bid، اذهب للكنترولر وشغل دالة index
// $router->get('/bid', ['app\Controllers\BidController', 'index']);
?>