<?php
// models/Order.php

class Order {
    private $conn;

    public $id;
    public $user_id;
    public $event_id;
    public $total_amount;
    public $status; // pending, paid, refunded, cancelled

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createMockOrder($ticket_type_id, $price) {
        try {
            // Start transaction
            $this->conn->beginTransaction();

            // 1. Create order
            $queryOrder = "INSERT INTO orders (user_id, event_id, total_amount, status) VALUES (:user_id, :event_id, :total_amount, 'paid')";
            $stmtOrder = $this->conn->prepare($queryOrder);
            $stmtOrder->bindParam(':user_id', $this->user_id);
            $stmtOrder->bindParam(':event_id', $this->event_id);
            $stmtOrder->bindParam(':total_amount', $price);
            $stmtOrder->execute();
            
            $this->id = $this->conn->lastInsertId();

            // 2. Decrement ticket quantity (Simple logical lock simulation)
            $queryUpdateQty = "UPDATE ticket_types SET quantity_available = quantity_available - 1 WHERE id = :type_id AND quantity_available > 0";
            $stmtUpdate = $this->conn->prepare($queryUpdateQty);
            $stmtUpdate->bindParam(':type_id', $ticket_type_id);
            $stmtUpdate->execute();

            if ($stmtUpdate->rowCount() == 0) {
                // No tickets left
                $this->conn->rollBack();
                return false;
            }

            // 3. Create Order Item with QR Hash
            $qr_hash = hash('sha256', uniqid($this->id . rand(), true));
            $queryItem = "INSERT INTO order_items (order_id, ticket_type_id, price, qr_code) VALUES (:order_id, :ticket_type_id, :price, :qr_code)";
            $stmtItem = $this->conn->prepare($queryItem);
            $stmtItem->bindParam(':order_id', $this->id);
            $stmtItem->bindParam(':ticket_type_id', $ticket_type_id);
            $stmtItem->bindParam(':price', $price);
            $stmtItem->bindParam(':qr_code', $qr_hash);
            $stmtItem->execute();

            // Commit transaction
            $this->conn->commit();
            return $qr_hash;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getCustomerOrders($user_id) {
        $query = "SELECT o.id as order_id, o.status, e.title as event_title, e.event_date, i.qr_code, i.is_used, i.price 
                  FROM orders o 
                  JOIN events e ON o.event_id = e.id 
                  JOIN order_items i ON o.id = i.order_id 
                  WHERE o.user_id = :user_id
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
