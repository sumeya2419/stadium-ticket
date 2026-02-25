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
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register User
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " (name, email, password, phone, role) VALUES (:name, :email, :password, :phone, :role)";
        $stmt = $this->conn->prepare($query);

        // Sanitize & hash
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // Bind
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":role", $this->role);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Login User
    public function login($email, $password) {
        $query = "SELECT id, name, email, password, role FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
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
