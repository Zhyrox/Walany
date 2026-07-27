<?php
require_once __DIR__ . '/../../../core/Database.php';

class HomepageModel {
    private $db;

    public function __construct() {
        $dbInstance = new Database();
        $this->db = $dbInstance->getConnection();
    }

    public function getActiveEventsWithRegistrations() {
        $query = "
            SELECT 
                e.*,
                COUNT(CASE WHEN r.payment_status = 'completed' THEN r.id END) AS current_registrations
            FROM `walania_event` e
            LEFT JOIN `walania_registrant` r ON e.id = r.event_id
            WHERE e.`is_active` = 1
            GROUP BY e.id
            ORDER BY e.`event_date` ASC
        ";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeaturedEvents() {
        $query = "
            SELECT 
                e.*,
                COUNT(r.id) AS current_registrations
            FROM `walania_event` e
            LEFT JOIN `walania_registrant` r ON e.id = r.event_id
            WHERE e.`is_active` = 1 AND e.`is_featured` = 1
            GROUP BY e.id
            ORDER BY e.`event_date` ASC
        ";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}