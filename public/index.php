<?php
// public/index.php

// Define include path for helper/model/layout loading
define('BASE_PATH', dirname(__DIR__));

// Load Core and Middleware
require_once BASE_PATH . '/src/Core/Security.php';
require_once BASE_PATH . '/src/Middleware/AuthMiddleware.php';
require_once BASE_PATH . '/models/User.php';

use App\Core\Security;
use App\Middleware\AuthMiddleware;

// Secure session start
Security::secureSessionStart();

// Simple Routing logic
$request = $_SERVER['REQUEST_URI'];
$base_path = '/stadium ticket/public'; // Adjust based on your local server root
$route = str_replace($base_path, '', $request);
$route = parse_url($route, PHP_URL_PATH);

require_once BASE_PATH . '/controllers/AuthController.php';
require_once BASE_PATH . '/controllers/AdminEventsController.php';
require_once BASE_PATH . '/controllers/CheckoutController.php';
require_once BASE_PATH . '/controllers/ScannerController.php';

$auth = new AuthController();
$adminEvents = new AdminEventsController();
$checkoutCtrl = new CheckoutController();
$scannerCtrl = new ScannerController();

switch ($route) {
    case '/':
    case '':
        include BASE_PATH . '/views/layout/header.php';
        if (isset($_SESSION['success'])) {
            echo '<div style="background:#d4edda; color:#155724; padding:10px; margin-bottom:20px;">' . Security::clean($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }
        echo '<h1>Welcome to StadiumPass</h1><p>World-class ticketing experience for the ultimate sports fans.</p>';
        include BASE_PATH . '/views/layout/footer.php';
        break;
    
    case '/events':
        $checkoutCtrl->showPublicEvents();
        break;

    case '/checkout':
        AuthMiddleware::auth();
        $checkoutCtrl->checkout();
        break;

    case '/login':
        include BASE_PATH . '/views/layout/header.php';
        if (isset($_SESSION['error'])) {
            echo '<div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:20px;">' . Security::clean($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        include BASE_PATH . '/views/auth/login.php';
        include BASE_PATH . '/views/layout/footer.php';
        break;

    case '/login-process':
        $auth->login();
        break;

    case '/register':
        include BASE_PATH . '/views/layout/header.php';
        include BASE_PATH . '/views/auth/register.php';
        include BASE_PATH . '/views/layout/footer.php';
        break;

    case '/register-process':
        $auth->register();
        break;

    case '/logout':
        $auth->logout();
        break;

    case '/dashboard':
        AuthMiddleware::role('customer');
        $checkoutCtrl->showCustomerDashboard();
        break;

    case '/select-seats':
        AuthMiddleware::auth();
        $checkoutCtrl->showSeatSelection();
        break;

    case '/api/seats':
        $checkoutCtrl->getSeatsJson();
        break;

    case '/api/reserve':
        $checkoutCtrl->reserveSeat();
        break;

    case '/payment':
        require_once BASE_PATH . '/controllers/PaymentController.php';
        $paymentCtrl = new PaymentController();
        $paymentCtrl->showGateway();
        break;

    case '/payment-process':
        require_once BASE_PATH . '/controllers/PaymentController.php';
        $paymentCtrl = new PaymentController();
        $paymentCtrl->processPayment();
        break;

    case '/invoice':
        require_once BASE_PATH . '/controllers/PaymentController.php';
        $paymentCtrl = new PaymentController();
        $paymentCtrl->showInvoice();
        break;

    case '/admin/dashboard':
        AuthMiddleware::role(['super_admin', 'admin']);
        $adminEvents->dashboard();
        break;

    case '/admin/venue/create':
        AuthMiddleware::role(['super_admin', 'admin']);
        $adminEvents->createVenue();
        break;

    case '/admin/event/create':
        AuthMiddleware::role(['super_admin', 'admin']);
        $adminEvents->createEvent();
        break;

    case '/staff/scanner':
        AuthMiddleware::role('staff');
        $scannerCtrl->loadUI();
        break;

    case '/staff/scan-process':
        AuthMiddleware::role('staff');
        $scannerCtrl->processScanRequest();
        break;

    default:
        http_response_code(404);
        include BASE_PATH . '/views/layout/header.php';
        echo '<h1>404 - Page Not Found</h1>';
        include BASE_PATH . '/views/layout/footer.php';
        break;
}
