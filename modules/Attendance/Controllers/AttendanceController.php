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
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => 'success',
                'events' => $events
            ];
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to initialize events roster: ' . $e->getMessage()
            ];
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

            // 1. Look up the student registration record using the scanned Reference ID
            // NOTE: Change 'walania_registrants' table name if your table name uses a different spelling
            $registrantStmt = $db->prepare("SELECT `first_name`, `last_name` FROM `walania_registrant` WHERE `reference_id` = :reference_id LIMIT 1");
            $registrantStmt->execute(['reference_id' => $referenceId]);
            $registrant = $registrantStmt->fetch(PDO::FETCH_ASSOC);

            if (!$registrant) {
                return [
                    'status' => 'error', 
                    'message' => "Invalid Code. No record matches Reference ID: " . htmlspecialchars($referenceId)
                ];
            }

            // 2. Security Guard: Prevent checking in the same Reference ID twice for the same event
            $duplicateCheck = $db->prepare("SELECT `id` FROM `walania_attendance` WHERE `reference_id` = :reference_id AND `event_id` = :event_id LIMIT 1");
            $duplicateCheck->execute([
                'reference_id' => $referenceId,
                'event_id'     => $eventId
            ]);
            
            if ($duplicateCheck->fetch()) {
                return [
                    'status' => 'error',
                    'message' => htmlspecialchars($registrant['first_name'] . ' ' . $registrant['last_name']) . " has already checked into this event."
                ];
            }

            // 3. Perfect Match: Save the clean record down to your simplified schema
            $insertStmt = $db->prepare("INSERT INTO `walania_attendance` (`reference_id`, `event_id`, `first_name`, `last_name`, `time_checked_in`) VALUES (:reference_id, :event_id, :first_name, :last_name, NOW())");
            
            $insertStmt->execute([
                'reference_id' => $referenceId,
                'event_id'     => $eventId,
                'first_name'   => $registrant['first_name'],
                'last_name'    => $registrant['last_name']
            ]);

            return [
                'status' => 'success',
                'message' => 'Checked In: ' . htmlspecialchars($registrant['first_name'] . ' ' . $registrant['last_name'])
            ];

        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database tracking breakdown: ' . $e->getMessage()];
        }
    }
}