<?php

namespace App\Middleware;

use App\Core\Database;
use User;

class AuthMiddleware {
    /**
     * Ensure user is logged in
     */
    public static function auth() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Authentication required.";
            header("Location: /login");
            exit();
        }
    }

    /**
     * Check if user has required role(s)
     */
    public static function role($allowed_roles) {
        self::auth();
        
        $role_name = $_SESSION['role_name'] ?? '';
        
        if (!in_array($role_name, (array)$allowed_roles)) {
            $_SESSION['error'] = "Unauthorized access.";
            header("Location: /");
            exit();
        }
    }

    /**
     * Check granular permission
     */
    public static function permission($permission) {
        self::auth();
        
        require_once BASE_PATH . '/models/User.php';
        $db = Database::getInstance();
        $user = new User($db);
        $user->role_id = $_SESSION['role_id'];
        
        if (!$user->hasPermission($permission)) {
            $_SESSION['error'] = "You do not have permission to perform this action.";
            header("Location: /");
            exit();
        }
    }
}
