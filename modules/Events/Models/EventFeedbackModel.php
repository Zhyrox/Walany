<?php
require_once __DIR__ . '/../../../core/Database.php';

class EventFeedback {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function saveFeedback($data) {
        try {
            // Defensive Check: Ensure this reference_id code exists AND matches this event track!
            $checkSql = "SELECT COUNT(*) FROM walania_registrant WHERE reference_id = :ref AND event_id = :event_id";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([
                ':ref'       => $data['reference_id'],
                ':event_id'  => $data['event_id']
            ]);
            
            if ($checkStmt->fetchColumn() == 0) {
                return false; // Rejects entry if reference code doesn't match this event context
            }

            // Insert into legacy table using the updated column layout
            $sql = "INSERT INTO walania_event_feedback (reference_id, event_id, comment, rating)
                    VALUES (:reference_id, :event_id, :comment, :rating)";
            
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':reference_id' => $data['reference_id'],
                ':event_id'     => $data['event_id'],
                ':comment'      => $data['comment'],
                ':rating'       => $data['rating']
            ]);
        } catch (PDOException $e) {
            // 1. Log it locally to identify which model query failed
            error_log("MODEL ENGINE FAILURE: " . $e->getMessage());
            
            // 2. Re-throw it so the Controller's catch block can catch it and redirect the user
            throw $e;
        }
    }

    public function getEventById($eventId) {
        try {
            $sql = "SELECT id, name FROM walania_event WHERE id = :event_id AND is_active = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':event_id' => $eventId]);
            return $stmt->fetch(PDO::FETCH_ASSOC); // Returns array like ['id' => 1, 'name' => 'Tech Summit 2026'] or false
        } catch (PDOException $e) {
            error_log("EVENT FETCH FAILURE: " . $e->getMessage());
            return null;
        }
    }

    public function getEventNameById($eventId) {
        try {
            $sql = "SELECT name FROM walania_event WHERE id = :event_id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':event_id' => $eventId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ? $row['name'] : null;
        } catch (PDOException $e) {
            error_log("MODEL ENGINE FAILURE (getEventNameById): " . $e->getMessage());
            return null;
        }
    }
}