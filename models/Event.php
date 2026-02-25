<?php
// models/Event.php

class Event {
    private $conn;
    private $table_name = "events";

    public $id;
    public $title;
    public $venue_id;
    public $event_date;
    public $start_time;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (title, venue_id, event_date, start_time, status) VALUES (:title, :venue_id, :event_date, :start_time, :status)";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->venue_id = htmlspecialchars(strip_tags($this->venue_id));
        $this->event_date = htmlspecialchars(strip_tags($this->event_date));
        $this->start_time = htmlspecialchars(strip_tags($this->start_time));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":venue_id", $this->venue_id);
        $stmt->bindParam(":event_date", $this->event_date);
        $stmt->bindParam(":start_time", $this->start_time);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT e.*, v.name as venue_name FROM " . $this->table_name . " e LEFT JOIN venues v ON e.venue_id = v.id ORDER BY event_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
