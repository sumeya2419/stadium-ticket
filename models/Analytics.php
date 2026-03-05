<?php
// models/Analytics.php

class Analytics {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get key performance indicators (KPIs)
     */
    public function getKPIs() {
        $stats = [];

        // Total Revenue
        $query = "SELECT SUM(total_amount) as total FROM orders WHERE status = 'paid'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Total Tickets Sold
        $query = "SELECT COUNT(*) as total FROM order_items i JOIN orders o ON i.order_id = o.id WHERE o.status = 'paid'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['tickets_sold'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Active Events
        $query = "SELECT COUNT(*) as total FROM events WHERE status = 'scheduled' AND event_date >= CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['active_events'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Total Capacity (Sum of all venues linked to active events)
        $query = "SELECT SUM(v.capacity) as total 
                  FROM venues v 
                  JOIN events e ON e.venue_id = v.id 
                  WHERE e.status = 'scheduled' AND e.event_date >= CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_capacity'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 1; // Avoid division by zero

        return $stats;
    }

    /**
     * Get recent sales activity
     */
    public function getRecentActivity($limit = 5) {
        $query = "SELECT o.id, u.name as user_name, e.title as event_title, o.total_amount, o.status, o.created_at 
                  FROM orders o
                  JOIN users u ON o.user_id = u.id
                  JOIN events e ON o.event_id = e.id
                  ORDER BY o.created_at DESC
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get occupancy rate per event
     */
    public function getEventOccupancy() {
        $query = "SELECT e.title, v.capacity, 
                  (SELECT COUNT(*) FROM order_items i JOIN orders o ON i.order_id = o.id WHERE o.event_id = e.id AND o.status = 'paid') as sold
                  FROM events e
                  JOIN venues v ON e.venue_id = v.id
                  WHERE e.status = 'scheduled' AND e.event_date >= CURDATE()
                  LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
