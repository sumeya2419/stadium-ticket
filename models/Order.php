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

    public function createMockOrder($ticket_type_id, $price, $seat_id = null) {
        try {
            // Start transaction
            $this->conn->beginTransaction();

            // 1. Create order
            $queryOrder = "INSERT INTO orders (user_id, event_id, total_amount, status) VALUES (:user_id, :event_id, :total_amount, 'pending')";
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

            // 3. Handle specific seat if provided
            if ($seat_id) {
                // Update seat status to blocked
                $querySeat = "UPDATE seats SET status = 'blocked' WHERE id = :seat_id AND status = 'available'";
                $stmtSeat = $this->conn->prepare($querySeat);
                $stmtSeat->bindParam(':seat_id', $seat_id);
                $stmtSeat->execute();

                if ($stmtSeat->rowCount() == 0) {
                    // Seat was taken or not available
                    $this->conn->rollBack();
                    return false;
                }

                // Delete the temporary reservation
                $queryDelRes = "DELETE FROM seat_reservations WHERE seat_id = :seat_id AND event_id = :event_id";
                $stmtDelRes = $this->conn->prepare($queryDelRes);
                $stmtDelRes->bindParam(':seat_id', $seat_id);
                $stmtDelRes->bindParam(':event_id', $this->event_id);
                $stmtDelRes->execute();
            }

            // 4. Create Order Item with QR Hash
            $qr_hash = hash('sha256', uniqid($this->id . rand(), true));
            $queryItem = "INSERT INTO order_items (order_id, ticket_type_id, price, qr_code, seat_id) VALUES (:order_id, :ticket_type_id, :price, :qr_code, :seat_id)";
            $stmtItem = $this->conn->prepare($queryItem);
            $stmtItem->bindParam(':order_id', $this->id);
            $stmtItem->bindParam(':ticket_type_id', $ticket_type_id);
            $stmtItem->bindParam(':price', $price);
            $stmtItem->bindParam(':qr_code', $qr_hash);
            $stmtItem->bindParam(':seat_id', $seat_id);
            $stmtItem->execute();

            // Commit transaction
            $this->conn->commit();
            return $qr_hash;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function updateStatus($order_id, $status) {
        $query = "UPDATE orders SET status = :status WHERE id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':order_id', $order_id);
        return $stmt->execute();
    }

    public function getOrderDetails($order_id) {
        $query = "SELECT o.*, e.title as event_title, e.event_date, e.start_time, v.name as venue_name, 
                         i.price, i.qr_code, i.seat_id, s.row_number, s.seat_number
                  FROM orders o
                  JOIN events e ON o.event_id = e.id
                  JOIN venues v ON e.venue_id = v.id
                  JOIN order_items i ON o.id = i.order_id
                  LEFT JOIN seats s ON i.seat_id = s.id
                  WHERE o.id = :order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
