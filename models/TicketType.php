<?php
// models/TicketType.php

class TicketType {
    private $conn;
    private $table_name = "ticket_types";

    public $id;
    public $event_id;
    public $name;
    public $price;
    public $quantity_available;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (event_id, name, price, quantity_available) VALUES (:event_id, :name, :price, :quantity_available)";
        $stmt = $this->conn->prepare($query);

        $this->event_id = htmlspecialchars(strip_tags($this->event_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->quantity_available = htmlspecialchars(strip_tags($this->quantity_available));

        $stmt->bindParam(":event_id", $this->event_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":quantity_available", $this->quantity_available);

        return $stmt->execute();
    }
}
?>
