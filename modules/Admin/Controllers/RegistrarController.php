<?php

require_once __DIR__ . '/../Models/RegistrarModel.php';

class RegistrarController {
    private $model;

    public function __construct() {
        $this->model = new RegistrarModel();
    }

    // Handles routing authentication and parameter calculations before rendering the dashboard
    public function registrarDashboard() {
        if (session_status() === PHP_SESSION_NONE) { 
            session_start(); 
        }

        // Security check
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'registrar') {
            header("Location: /Walany/index.php?module=Auth&action=login");
            exit;
        }

        // Gather Registrar identity details
        $currentRegistrarName = htmlspecialchars($_SESSION['manager_name'] ?? 'System Registrar');
        $currentRegistrarRole = htmlspecialchars(ucfirst($_SESSION['role'] ?? 'Registrar'));
        
        try {
            $registrarEmail = $this->model->getRegistrarEmail($_SESSION['manager_id']);
        } catch (Exception $e) {
            header("Location: /Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database operational error."));
            exit;
        }

        // Process Calendar Dates
        $month = isset($_GET['c_month']) ? intval($_GET['c_month']) : intval(date('m'));
        $year  = isset($_GET['c_year']) ? intval($_GET['c_year']) : intval(date('Y'));

        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $daysInMonth     = date('t', $firstDayOfMonth);
        $dayOfWeek       = date('w', $firstDayOfMonth);
        $monthName       = date('F', $firstDayOfMonth);

        // Prev / Next month configurations
        $prevMonth = $month - 1; $prevYear = $year;
        if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }

        $nextMonth = $month + 1; $nextYear = $year;
        if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

        // Gather grouped events and recent live updates
        $eventsByDate = $this->model->getEventsGroupedByDate();
        $recentAttendance = $this->model->getRecentAttendance(10);

        require_once __DIR__ . '/../Views/registrarDashboard.php';
    }
}