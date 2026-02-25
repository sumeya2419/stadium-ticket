<?php
// public/index.php
session_start();

// Simple Routing logic
$request = $_SERVER['REQUEST_URI'];
$base_path = '/stadium ticket/public'; // Adjust based on your local server root
$route = str_replace($base_path, '', $request);
$route = parse_url($route, PHP_URL_PATH);

// Define include path for helper/model/layout loading
define('BASE_PATH', dirname(__DIR__));

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
            echo '<div style="background:#d4edda; color:#155724; padding:10px; margin-bottom:20px;">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        echo '<h1>Welcome to StadiumTix</h1><p>Find and book tickets for the best events.</p>';
        include BASE_PATH . '/views/layout/footer.php';
        break;
    
    case '/events':
        $checkoutCtrl->showPublicEvents();
        break;

    case '/checkout':
        $checkoutCtrl->checkout();
        break;

    case '/login':
        include BASE_PATH . '/views/layout/header.php';
        if (isset($_SESSION['error'])) {
            echo '<div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:20px;">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div style="background:#d4edda; color:#155724; padding:10px; margin-bottom:20px;">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        include BASE_PATH . '/views/auth/login.php';
        include BASE_PATH . '/views/layout/footer.php';
        break;

    case '/login-process':
        $auth->login();
        break;

    case '/register':
        include BASE_PATH . '/views/layout/header.php';
        if (isset($_SESSION['error'])) {
            echo '<div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:20px;">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
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
        $checkoutCtrl->showCustomerDashboard();
        break;

    case '/admin/dashboard':
        $adminEvents->dashboard();
        break;

    case '/admin/venue/create':
        $adminEvents->createVenue();
        break;

    case '/admin/event/create':
        $adminEvents->createEvent();
        break;

    case '/staff/scanner':
        $scannerCtrl->loadUI();
        break;

    case '/staff/scan-process':
        $scannerCtrl->processScanRequest();
        break;
    default:
        http_response_code(404);
        include BASE_PATH . '/views/layout/header.php';
        echo '<h1>404 - Page Not Found</h1>';
        include BASE_PATH . '/views/layout/footer.php';
        break;
}
?>
