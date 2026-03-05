<?php
// controllers/PaymentController.php
require_once BASE_PATH . '/src/Core/Security.php';
require_once BASE_PATH . '/src/Middleware/AuthMiddleware.php';
require_once BASE_PATH . '/models/Order.php';

use App\Core\Security;
use App\Middleware\AuthMiddleware;

class PaymentController {
    private $db;
    private $order;

    public function __construct() {
        require_once BASE_PATH . '/src/Core/Database.php';
        $this->db = \App\Core\Database::getInstance();
        $this->order = new Order($this->db);
    }

    /**
     * Show the mock payment gateway
     */
    public function showGateway() {
        AuthMiddleware::auth();
        $order_id = $_GET['order_id'] ?? null;
        if (!$order_id) {
            header("Location: /dashboard");
            exit();
        }

        $orderDetails = $this->order->getOrderDetails($order_id);
        if (!$orderDetails || $orderDetails['user_id'] != $_SESSION['user_id']) {
            header("Location: /dashboard");
            exit();
        }

        include BASE_PATH . '/views/layout/header.php';
        include BASE_PATH . '/views/payment/gateway.php';
        include BASE_PATH . '/views/layout/footer.php';
    }

    /**
     * Process the mock payment
     */
    public function processPayment() {
        AuthMiddleware::auth();
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Security validation failed (CSRF).";
            header("Location: /dashboard");
            exit();
        }
        $order_id = $_POST['order_id'] ?? null;
        
        // Simulate processing delay
        usleep(500000); 

        if ($order_id) {
            // In a real app, we'd verify with a payment provider here
            $this->order->updateStatus($order_id, 'paid');
            $_SESSION['success'] = "Payment successful! Your ticket is now active.";
            header("Location: /invoice?order_id=" . $order_id);
        } else {
            $_SESSION['error'] = "Payment failed. Please try again.";
            header("Location: /dashboard");
        }
        exit();
    }

    /**
     * Show the digital invoice
     */
    public function showInvoice() {
        AuthMiddleware::auth();
        $order_id = $_GET['order_id'] ?? null;
        if (!$order_id) {
            header("Location: /dashboard");
            exit();
        }

        $order = $this->order->getOrderDetails($order_id);
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            header("Location: /dashboard");
            exit();
        }

        include BASE_PATH . '/views/layout/header.php';
        include BASE_PATH . '/views/customer/invoice.php';
        include BASE_PATH . '/views/layout/footer.php';
    }
}
