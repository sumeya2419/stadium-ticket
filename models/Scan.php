<?php
// models/Scan.php

class Scan {
    private $conn;

    public $id;
    public $order_item_id;
    public $scanned_by;
    public $result;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function processScan($qr_code, $staff_id) {
        // 1. Find the order_item by qr_code
        $queryFind = "SELECT i.id, i.order_id, i.is_used, o.status as order_status 
                      FROM order_items i 
                      JOIN orders o ON i.order_id = o.id 
                      WHERE i.qr_code = :qr_code LIMIT 0,1";
        $stmtFind = $this->conn->prepare($queryFind);
        $stmtFind->bindParam(':qr_code', $qr_code);
        $stmtFind->execute();

        if ($stmtFind->rowCount() == 0) {
            return ['status' => 'invalid', 'message' => 'QR Code not found in the system.'];
        }

        $row = $stmtFind->fetch(PDO::FETCH_ASSOC);
        $item_id = $row['id'];
        $is_used = $row['is_used'];
        $order_status = $row['order_status'];

        if ($order_status != 'paid') {
            $this->logScan($item_id, $staff_id, 'invalid');
            return ['status' => 'invalid', 'message' => 'Order for this ticket is not paid.'];
        }

        if ($is_used) {
            $this->logScan($item_id, $staff_id, 'already_used');
            return ['status' => 'already_used', 'message' => 'This ticket has already been used.'];
        }

        // 2. Mark as used
        $queryUpdate = "UPDATE order_items SET is_used = 1 WHERE id = :item_id";
        $stmtUpdate = $this->conn->prepare($queryUpdate);
        $stmtUpdate->bindParam(':item_id', $item_id);
        
        if ($stmtUpdate->execute()) {
            $this->logScan($item_id, $staff_id, 'valid');
            return ['status' => 'valid', 'message' => 'Ticket is valid! Access granted.'];
        }

        return ['status' => 'error', 'message' => 'System error processing ticket.'];
    }

    private function logScan($item_id, $staff_id, $result) {
        $query = "INSERT INTO scans (order_item_id, scanned_by, result) VALUES (:item_id, :staff_id, :result)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':staff_id', $staff_id);
        $stmt->bindParam(':result', $result);
        $stmt->execute();
    }
}
?>
