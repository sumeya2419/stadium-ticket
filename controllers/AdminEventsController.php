<?php
// controllers/AdminEventsController.php
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/models/Venue.php';
require_once BASE_PATH . '/models/Event.php';
require_once BASE_PATH . '/models/TicketType.php';

class AdminEventsController {
    private $db;
    private $venue;
    private $event;
    private $ticket_type;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->venue = new Venue($this->db);
        $this->event = new Event($this->db);
        $this->ticket_type = new TicketType($this->db);
    }

    public function createVenue() {
        AuthController::checkRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->venue->name = $_POST['name'];
            $this->venue->location = $_POST['location'];
            $this->venue->capacity = $_POST['capacity'];
            
            if ($this->venue->create()) {
                $_SESSION['success'] = "Venue created successfully!";
            } else {
                $_SESSION['error'] = "Failed to create venue.";
            }
            header("Location: /admin/dashboard");
            exit();
        }
    }

    public function createEvent() {
        AuthController::checkRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->event->title = $_POST['title'];
            $this->event->venue_id = $_POST['venue_id'];
            $this->event->event_date = $_POST['event_date'];
            $this->event->start_time = $_POST['start_time'];
            $this->event->status = 'scheduled';
            
            if ($this->event->create()) {
                // Also create generic ticket type if provided
                if (!empty($_POST['ticket_name']) && !empty($_POST['ticket_price']) && !empty($_POST['ticket_quantity'])) {
                    $this->ticket_type->event_id = $this->event->id;
                    $this->ticket_type->name = $_POST['ticket_name'];
                    $this->ticket_type->price = $_POST['ticket_price'];
                    $this->ticket_type->quantity_available = $_POST['ticket_quantity'];
                    $this->ticket_type->create();
                }

                $_SESSION['success'] = "Event created successfully!";
            } else {
                $_SESSION['error'] = "Failed to create event.";
            }
            header("Location: /admin/dashboard");
            exit();
        }
    }

    public function dashboard() {
        AuthController::checkRole(['admin']);
        $venues = $this->venue->readAll();
        $events = $this->event->readAll();
        
        include BASE_PATH . '/views/layout/header.php';
        include BASE_PATH . '/views/admin/dashboard.php';
        include BASE_PATH . '/views/layout/footer.php';
    }
}
?>
