<?php
require_once __DIR__ . '/../../../core/Database.php';

class HomepageController {
    public function index() {
        try {
            $dbInstance = new Database();
            $db = $dbInstance->getConnection();
            
            // Extract live data matrices (ORDER BY event_date ASC shows soonest events first)
            // Tip: You can change this to "WHERE `event_date` >= CURDATE()" if you want to automatically hide past events!
            $stmt = $db->query("SELECT * FROM `walania_event` ORDER BY `event_date` ASC");
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            // 1. Log the absolute descriptive raw traceback details to XAMPP error logs for the server administrator
            error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());

            // 2. Safely redirect the user to the generic error container view without leaking structure schemas
            header("Location: /Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
            exit;
        }

        // Load the view layout file safely now that $events is populated with the new thumbnail column
        require_once __DIR__ . '/../Views/homepage.php';
    }
}
?>