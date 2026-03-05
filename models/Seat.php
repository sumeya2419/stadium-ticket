<?php

class Seat {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get all seats for a specific event with their current status (available, reserved, or blocked)
     */
    public function getSeatsForEvent($event_id) {
        $query = "SELECT s.*, 
                  CASE 
                    WHEN s.status = 'blocked' THEN 'occupied'
                    WHEN sr.id IS NOT NULL AND sr.expires_at > NOW() THEN 'reserved'
                    ELSE 'available'
                  END as current_status,
                  sr.user_id as reserved_by
                  FROM seats s
                  JOIN sections sec ON s.section_id = sec.id
                  JOIN events e ON sec.venue_id = e.venue_id
                  LEFT JOIN seat_reservations sr ON s.id = sr.seat_id AND sr.event_id = :event_id
                  WHERE e.id = :event_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Attempt to reserve a seat temporarily
     */
    public function reserve($seat_id, $event_id, $user_id, $minutes = 10) {
        try {
            $this->conn->beginTransaction();

            // Check if seat is already occupied or reserved
            $checkQuery = "SELECT id FROM seats WHERE id = :seat_id AND status = 'blocked'";
            $stmtCheck = $this->conn->prepare($checkQuery);
            $stmtCheck->bindParam(':seat_id', $seat_id);
            $stmtCheck->execute();
            if ($stmtCheck->rowCount() > 0) return false;

            $resQuery = "SELECT id FROM seat_reservations WHERE seat_id = :seat_id AND event_id = :event_id AND expires_at > NOW()";
            $stmtRes = $this->conn->prepare($resQuery);
            $stmtRes->bindParam(':seat_id', $seat_id);
            $stmtRes->bindParam(':event_id', $event_id);
            $stmtRes->execute();
            if ($stmtRes->rowCount() > 0) return false;

            // Create reservation
            $expires_at = date('Y-m-d H:i:s', strtotime("+$minutes minutes"));
            $query = "INSERT INTO seat_reservations (seat_id, event_id, user_id, expires_at) 
                      VALUES (:seat_id, :event_id, :user_id, :expires_at)
                      ON DUPLICATE KEY UPDATE user_id = :user_id2, expires_at = :expires_at2";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':seat_id', $seat_id);
            $stmt->bindParam(':event_id', $event_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':expires_at', $expires_at);
            // Repeat bindings for ON DUPLICATE KEY
            $stmt->bindParam(':user_id2', $user_id);
            $stmt->bindParam(':expires_at2', $expires_at);
            
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    /**
     * Release a reservation
     */
    public function release($seat_id, $event_id, $user_id) {
        $query = "DELETE FROM seat_reservations WHERE seat_id = :seat_id AND event_id = :event_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':seat_id', $seat_id);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
}
