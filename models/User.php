<?php
// models/User.php

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $role_id;
    public $role_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register User
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " (name, email, password, phone, role_id) VALUES (:name, :email, :password, :phone, :role_id)";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->role_id = filter_var($this->role_id, FILTER_VALIDATE_INT);
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // Bind
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":role_id", $this->role_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Login User
    public function login($email, $password) {
        $query = "SELECT u.id, u.name, u.email, u.password, u.role_id, r.name as role_name 
                  FROM " . $this->table_name . " u 
                  JOIN roles r ON u.role_id = r.id 
                  WHERE u.email = :email AND u.status = 'active' LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->role_id = $row['role_id'];
                $this->role_name = $row['role_name'];
                return true;
            }
        }
        return false;
    }

    // Check Perkission
    public function hasPermission($permission_name) {
        $query = "SELECT p.id FROM permissions p 
                  JOIN role_permissions rp ON p.id = rp.permission_id 
                  WHERE rp.role_id = :role_id AND p.name = :permission";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":role_id", $this->role_id);
        $stmt->bindParam(":permission", $permission_name);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Check if email exists
    public function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>
