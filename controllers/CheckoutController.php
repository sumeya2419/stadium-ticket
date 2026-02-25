<?php
// controllers/CheckoutController.php
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/models/Order.php';
require_once BASE_PATH . '/models/Event.php';

class CheckoutController {
    private $db;
    private $order;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->order = new Order($this->db);
    }

    public function checkout() {
        AuthController::checkRole(['customer']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id']) && isset($_POST['ticket_type_id'])) {
            $user_id = $_SESSION['user_id'];
            $event_id = $_POST['event_id'];
            $ticket_type_id = $_POST['ticket_type_id'];
            $price = $_POST['price'];

            $this->order->user_id = $user_id;
            $this->order->event_id = $event_id;
            
            // This runs the transaction simulating mock payment and seating updates
            $qr_code = $this->order->createMockOrder($ticket_type_id, $price);

            if ($qr_code) {
                $_SESSION['success'] = "Payment successful! Your ticket has been generated.";
            } else {
                $_SESSION['error'] = "Checkout failed. Tickets might be sold out.";
            }
            header("Location: /dashboard");
            exit();
        } else {
            header("Location: /events");
            exit();
        }
    }

    public function showPublicEvents() {
        $eventModel = new Event($this->db);
        $events = $eventModel->readAll();
        
        // Fetch ticket types for simplicity directly via PDO here
        $query = "SELECT * FROM ticket_types";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $allTickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ticketsByEvent = [];
        foreach($allTickets as $t) {
            $ticketsByEvent[$t['event_id']][] = $t;
        }

        include BASE_PATH . '/views/layout/header.php';
        include BASE_PATH . '/views/public/events.php';
        include BASE_PATH . '/views/layout/footer.php';
    }

    public function showCustomerDashboard() {
        AuthController::checkRole(['customer']);
        $user_id = $_SESSION['user_id'];
        
        $myOrders = $this->order->getCustomerOrders($user_id);

        include BASE_PATH . '/views/layout/header.php';
        include BASE_PATH . '/views/customer/dashboard.php';
        include BASE_PATH . '/views/layout/footer.php';
    }
}
?>
