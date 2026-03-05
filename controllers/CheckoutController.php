<?php
// controllers/CheckoutController.php
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/models/Order.php';
require_once BASE_PATH . '/models/Event.php';

class CheckoutController {
    private $db;
    private $order;

    public function __construct() {
        require_once BASE_PATH . '/src/Core/Database.php';
        $this->db = \App\Core\Database::getInstance();
        $this->order = new Order($this->db);
    }

    public function checkout() {
        \App\Middleware\AuthMiddleware::auth();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id']) && isset($_POST['ticket_type_id'])) {
            if (!\App\Core\Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "Security validation failed (CSRF).";
                header("Location: /events");
                exit();
            }
            $user_id = $_SESSION['user_id'];
            $event_id = $_POST['event_id'];
            $ticket_type_id = $_POST['ticket_type_id'];
            $price = $_POST['price'];
            $seat_id = $_POST['seat_id'] ?? null;

            $this->order->user_id = $user_id;
            $this->order->event_id = $event_id;
            
            // This runs the transaction simulating mock payment and seating updates
            $qr_code = $this->order->createMockOrder($ticket_type_id, $price, $seat_id);

            if ($qr_code) {
                // Redirect to payment gateway instead of dashboard
                header("Location: /payment?order_id=" . $this->order->id);
            } else {
                $_SESSION['error'] = "Checkout failed. Tickets might be sold out or seat is no longer available.";
                header("Location: /dashboard");
            }
            exit();
        } else {
            header("Location: /events");
            exit();
        }
    }

    /**
     * AJAX Endpoint: Get seats for an event
     */
    public function getSeatsJson() {
        \App\Middleware\AuthMiddleware::auth();
        $event_id = $_GET['event_id'] ?? null;
        if (!$event_id) {
            echo json_encode(['error' => 'Event ID required']);
            return;
        }

        require_once BASE_PATH . '/models/Seat.php';
        $seatModel = new Seat($this->db);
        $seats = $seatModel->getSeatsForEvent($event_id);
        
        header('Content-Type: application/json');
        echo json_encode($seats);
    }

    /**
     * AJAX Endpoint: Reserve a seat
     */
    public function reserveSeat() {
        \App\Middleware\AuthMiddleware::auth();
        $data = json_decode(file_get_contents('php://input'), true);
        $src_seat_id = $data['seat_id'] ?? null;
        $src_event_id = $data['event_id'] ?? null;
        $user_id = $_SESSION['user_id'] ?? null;

        if (!$src_seat_id || !$src_event_id || !$user_id) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters or not logged in']);
            return;
        }

        require_once BASE_PATH . '/models/Seat.php';
        $seatModel = new Seat($this->db);
        $success = $seatModel->reserve($src_seat_id, $src_event_id, $user_id);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }

    public function showPublicEvents() {
        $eventModel = new Event($this->db);
        $events = $eventModel->readAll();
        
        // Fetch ticket types
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

    public function showSeatSelection() {
        \App\Middleware\AuthMiddleware::auth();
        $event_id = $_GET['event_id'] ?? null;
        if (!$event_id) {
            header("Location: /events");
            exit();
        }

        include BASE_PATH . '/views/layout/header.php';
        include BASE_PATH . '/views/public/seat_selection.php';
        include BASE_PATH . '/views/layout/footer.php';
    }

    public function showCustomerDashboard() {
        \App\Middleware\AuthMiddleware::auth();
        $user_id = $_SESSION['user_id'];
        
        $myOrders = $this->order->getCustomerOrders($user_id);

        include BASE_PATH . '/views/layout/header.php';
        include BASE_PATH . '/views/customer/dashboard.php';
        include BASE_PATH . '/views/layout/footer.php';
    }
}
?>
