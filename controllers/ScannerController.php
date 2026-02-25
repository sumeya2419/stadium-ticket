<?php
// controllers/ScannerController.php
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/models/Scan.php';

class ScannerController {
    private $db;
    private $scan;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->scan = new Scan($this->db);
    }

    public function loadUI() {
        AuthController::checkRole(['staff', 'admin']);
        include BASE_PATH . '/views/layout/header.php';
        include BASE_PATH . '/views/staff/scanner.php';
        include BASE_PATH . '/views/layout/footer.php';
    }

    public function processScanRequest() {
        AuthController::checkRole(['staff', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['qr_code'])) {
            $qr_code = trim($_POST['qr_code']);
            $staff_id = $_SESSION['user_id'];

            if (empty($qr_code)) {
                $_SESSION['scan_result'] = ['status' => 'invalid', 'message' => 'QR Code cannot be empty.'];
            } else {
                $_SESSION['scan_result'] = $this->scan->processScan($qr_code, $staff_id);
            }
            
            header("Location: /staff/scanner");
            exit();
        }
    }
}
?>
