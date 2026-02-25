<?php
// controllers/AuthController.php
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->name = $_POST['name'];
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password'];
            $this->user->phone = $_POST['phone'] ?? '';
            $this->user->role = 'customer'; // Default role

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
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->user->login($email, $password)) {
                // Security: Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['user_name'] = $this->user->name;
                $_SESSION['user_role'] = $this->user->role;

                // Redirect based on role
                if ($this->user->role == 'admin') {
                    header("Location: /admin/dashboard");
                } elseif ($this->user->role == 'staff') {
                    header("Location: /staff/scanner");
                } else {
                    header("Location: /dashboard");
                }
            } else {
                $_SESSION['error'] = "Invalid email or password.";
                header("Location: /login");
            }
            exit();
        }
    }

    public function logout() {
        session_destroy();
        header("Location: /login");
        exit();
    }

    public static function checkRole($roles) {
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $roles)) {
            header("Location: /login");
            exit();
        }
    }
}
?>
