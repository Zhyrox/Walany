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
            // Log or gracefully display database failures
            echo "<div style='color:red; background:#fff3f3; padding:15px; border-radius:5px; margin:20px auto; max-width:1200px;'><strong>Database Query Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
            $events = [];
        }

        // Load the view layout file safely now that $events is populated with the new thumbnail column
        require_once __DIR__ . '/../Views/homepage.php';
    }
}
?>