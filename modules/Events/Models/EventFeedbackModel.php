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
            return false;
        }
    }
}