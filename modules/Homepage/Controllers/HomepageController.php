<?php
require_once __DIR__ . '/../Models/HomepageModel.php';

class HomepageController {
    public function index() {
        try {
            $HomepageModel = new HomepageModel();
            
            $featuredEvents = $HomepageModel->getFeaturedEvents();
            $events = $HomepageModel->getActiveEventsWithRegistrations();
            
        } catch (PDOException $e) {
            error_log("Database fault: " . $e->getMessage());
            header("Location: /Walany/index.php?module=Admin&action=system_error");
            exit;
        }

        require_once __DIR__ . '/../Views/homepage.php';
    }
}