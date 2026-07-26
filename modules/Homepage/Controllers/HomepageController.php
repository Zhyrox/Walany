<?php
require_once __DIR__ . '/../Models/HomepageModel.php';

class HomepageController {
    public function index() {
        try {
            $HomepageModel = new HomepageModel();
            $events = $HomepageModel->getActiveEventsWithRegistrations();
            
        } catch (PDOException $e) {
            error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            header("Location: /Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
            exit;
        }

        // Pass $events to the View
        require_once __DIR__ . '/../Views/homepage.php';
    }
}