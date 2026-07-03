<?php
require_once __DIR__ . '/../../../core/Database.php';

class HomepageController {
    public function index() {
        try {
            $dbInstance = new Database();
            $db = $dbInstance->getConnection();
            
            // Extract live data matrices
            $stmt = $db->query("SELECT * FROM `walania_event` ORDER BY `event_date` ASC");
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log or gracefully display database failures
            echo "<div style='color:red; background:#fff3f3; padding:15px;'><strong>Database Query Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
            $events = [];
        }

        // Load the view layout file safely now that $events is populated
        require_once __DIR__ . '/../Views/homepage.php';
    }
}
?>