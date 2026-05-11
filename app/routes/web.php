<?php
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ProfileController;
use App\Controllers\DashboardController;
use App\Controllers\BidController;
use App\Controllers\PageController;
use App\Controllers\ProjectController;
use App\Controllers\ChatController;
use App\Controllers\NotificationController;
use App\Controllers\WalletController;


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
$router->get('/browse-experts', [PageController::class, 'browseExperts']);
$router->get('/browse-jobs', [PageController::class, 'browseJobs']);
$router->get('/incoming-bids', [PageController::class, 'incomingBids']);
$router->get('/chat', [ChatController::class, 'index']);
$router->post('/chat/send', [ChatController::class, 'send']);
$router->get('/chat/messages', [ChatController::class, 'poll']);
$router->get('/dispute', [PageController::class, 'dispute']);
$router->get('/admin', [PageController::class, 'adminDashboard']);

// Wallet
$router->get('/wallet', [WalletController::class, 'index']);
$router->get('/wallet/transactions', [WalletController::class, 'transactions']);
$router->post('/wallet/fund', [WalletController::class, 'fund']);

// Notifications
$router->get('/notifications', [NotificationController::class, 'index']);
$router->post('/notifications/read', [NotificationController::class, 'markRead']);
$router->post('/notifications/read-all', [NotificationController::class, 'markAllRead']);
$router->post('/notifications/dismiss', [NotificationController::class, 'dismiss']);
$router->get('/notifications/count', [NotificationController::class, 'count']);

//Profile 
$router->get('/profile',          [ProfileController::class, 'index']);
$router->get('/profile/setup',    [ProfileController::class, 'setup']);
$router->post('/profile/setup',   [ProfileController::class, 'store']);
$router->get('/profile/edit',     [ProfileController::class, 'edit']);
$router->post('/profile/update',  [ProfileController::class, 'update']);
$router->post('/profile/kyc/delete', [ProfileController::class, 'deleteKycDoc']);

// Bid 
$router->get('/bid',              [BidController::class, 'index']);
$router->post('/bid',             [BidController::class, 'store']);

//bid-review
$router->get('/bid-review',              [BidController::class, 'index2']);
$router->post('/bid-review',             [BidController::class, 'store2']);


//project
$router->get('/post-job', [ProjectController::class, 'postJob']);
$router->post('/post-job', [ProjectController::class, 'store']);

$router->get('/job-view', [ProjectController::class, 'Jobview']);
$router->post('/job-view', [ProjectController::class, 'store2']);

$router->get('/project-detail', [ProjectController::class, 'ProjectDetail']);

$router->get('/project-detail(in-dispute)', [ProjectController::class, 'ProjectDetailInDispute']);


?>
