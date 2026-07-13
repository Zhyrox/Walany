<?php
require_once __DIR__ . '/../../../core/Database.php';

class AttendanceController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) { 
            session_start(); 
        }
        
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'registrar') {
            header("Location: ?module=Auth&action=login&login_error=" . urlencode("Unauthorized entry checkpoint."));
            exit;
        }
    }

    public function showEventsList() {
        try {
            $db = (new Database())->getConnection();
            $stmt = $db->prepare("SELECT * FROM `walania_event` ORDER BY `event_date` DESC");
            $stmt->execute();
            return ['status' => 'success', 'events' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch (PDOException $e) {
            // 1. Log the absolute descriptive raw traceback details to XAMPP error logs for the server administrator
            error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());

            // 2. Safely redirect the user to the generic error container view without leaking structure schemas
            header("Location: /PHP_Project/Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
            exit;
        }
    }

    // Helper method to grab everyone who checked into this event
    public function getAttendeesList($eventId) {
        try {
            $db = (new Database())->getConnection();
            $stmt = $db->prepare("SELECT `reference_id`, `first_name`, `last_name`, `time_checked_in` FROM `walania_attendance` WHERE `event_id` = :event_id ORDER BY `time_checked_in` DESC");
            $stmt->execute(['event_id' => $eventId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // 1. Log the absolute descriptive raw traceback details to XAMPP error logs for the server administrator
            error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());

            // 2. Safely redirect the user to the generic error container view without leaking structure schemas
            header("Location: /PHP_Project/Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
            exit;
        }
    }

    public function processAttendanceScan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['status' => 'error', 'message' => 'Invalid transaction request method.'];
        }

        $referenceId = trim($_POST['qr_data'] ?? '');
        $eventId     = intval($_POST['event_id'] ?? 0);

        if (empty($referenceId) || empty($eventId)) {
            return ['status' => 'error', 'message' => 'Malformed dataset transmission values.'];
        }

        try {
            $db = (new Database())->getConnection();

            // 1. Look up student registration details, including email and phone number
            $registrantStmt = $db->prepare("SELECT `first_name`, `last_name`, `email`, `contact_number` FROM `walania_registrant` WHERE `reference_id` = :reference_id LIMIT 1");
            $registrantStmt->execute(['reference_id' => $referenceId]);
            $registrant = $registrantStmt->fetch(PDO::FETCH_ASSOC);

            if (!$registrant) {
                return [
                    'status' => 'error', 
                    'message' => "Invalid Code. No record matches Reference ID: " . htmlspecialchars($referenceId),
                    'attendees' => $this->getAttendeesList($eventId)
                ];
            }

            // 2. Duplicate Check
            $duplicateCheck = $db->prepare("SELECT `id` FROM `walania_attendance` WHERE `reference_id` = :reference_id AND `event_id` = :event_id LIMIT 1");
            $duplicateCheck->execute(['reference_id' => $referenceId, 'event_id' => $eventId]);
            
            if ($duplicateCheck->fetch()) {
                return [
                    'status' => 'error',
                    'message' => htmlspecialchars($registrant['first_name'] . ' ' . $registrant['last_name']) . " has already checked into this event.",
                    'registrant' => $registrant,
                    'reference_id' => $referenceId,
                    'attendees' => $this->getAttendeesList($eventId)
                ];
            }

            // 3. Insert record down to your simplified schema
            $insertStmt = $db->prepare("INSERT INTO `walania_attendance` (`reference_id`, `event_id`, `first_name`, `last_name`, `time_checked_in`) VALUES (:reference_id, :event_id, :first_name, :last_name, NOW())");
            $insertStmt->execute([
                'reference_id' => $referenceId,
                'event_id'     => $eventId,
                'first_name'   => $registrant['first_name'],
                'last_name'    => $registrant['last_name']
            ]);

            return [
                'status' => 'success',
                'message' => 'Successfully Checked In!',
                'reference_id' => $referenceId,
                'registrant' => $registrant,
                'attendees' => $this->getAttendeesList($eventId) // Return updated logs array to refresh the table
            ];

        } catch (PDOException $e) {
            // 1. Log the absolute descriptive raw traceback details to XAMPP error logs for the server administrator
            error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());

            // 2. Safely redirect the user to the generic error container view without leaking structure schemas
            header("Location: /PHP_Project/Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
            exit;
        }
    }
}