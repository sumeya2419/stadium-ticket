<?php
// controllers/AuthController.php
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        require_once BASE_PATH . '/src/Core/Database.php';
        $this->db = \App\Core\Database::getInstance();
        $this->user = new User($this->db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!\App\Core\Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "Security validation failed (CSRF).";
                header("Location: /register");
                exit();
            }
            $this->user->name = $_POST['name'];
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password'];
            $this->user->phone = $_POST['phone'] ?? '';
            $this->user->role_id = 5; // Default role: 'customer' (from seed)

            if ($this->user->emailExists()) {
                $_SESSION['error'] = "Email already exists.";
                header("Location: /register");
                exit();
            }

            if ($this->user->register()) {
                $_SESSION['success'] = "Registration successful. Please login.";
                header("Location: /login");
            } else {
                $_SESSION['error'] = "Something went wrong.";
                header("Location: /register");
            }
            exit();
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!\App\Core\Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "Security validation failed (CSRF).";
                header("Location: /login");
                exit();
            }
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->user->login($email, $password)) {
                // Security: Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['user_name'] = $this->user->name;
                $_SESSION['role_id'] = $this->user->role_id;
                $_SESSION['role_name'] = $this->user->role_name;

                // Redirect based on role
                if ($this->user->role_name == 'super_admin' || $this->user->role_name == 'admin') {
                    header("Location: /admin/dashboard");
                } elseif ($this->user->role_name == 'staff') {
                    header("Location: /staff/scanner");
                } else {
                    header("Location: /dashboard");
                }
            } else {
                $_SESSION['error'] = "Invalid email or matching record not found.";
                header("Location: /login");
            }
            exit();
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: /login");
        exit();
    }
}
?>
