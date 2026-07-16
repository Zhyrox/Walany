<?php
require_once __DIR__ . '/../../../core/Database.php';

class RegistrarModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    // Fetch registrar profile details by manager ID
    
    public function getRegistrarEmail($managerId) {
        try {
            $stmt = $this->db->prepare("SELECT email FROM `walania_managers` WHERE `id` = :id LIMIT 1");
            $stmt->execute(['id' => $managerId]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? $data['email'] : 'N/A';
        } catch (PDOException $e) {
            error_log("Database Error in getRegistrarEmail: " . $e->getMessage());
            throw $e;
        }
    }

    // Retrieve and group scheduled events by calendar date (YYYY-MM-DD)
    public function getEventsGroupedByDate() {
        try {
            $stmt = $this->db->query("SELECT id, name, event_date, location FROM `walania_event` WHERE event_date IS NOT NULL ORDER BY event_date ASC");
            $rawEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $eventsByDate = [];
            foreach ($rawEvents as $event) {
                $formattedDate = date('Y-m-d', strtotime($event['event_date']));
                $eventsByDate[$formattedDate][] = [
                    'id'       => $event['id'],
                    'name'     => htmlspecialchars($event['name']),
                    'location' => htmlspecialchars($event['location'] ?? 'N/A'),
                    'time'     => date('h:i A', strtotime($event['event_date']))
                ];
            }
            return $eventsByDate;
        } catch (PDOException $e) {
            error_log("Database Error in getEventsGroupedByDate: " . $e->getMessage());
            return [];
        }
    }

    // Fetch recent check-ins joined with event details
    public function getRecentAttendance($limit = 10) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    a.id,
                    a.reference_id,
                    a.first_name,
                    a.last_name,
                    a.time_checked_in,
                    e.name AS event_name
                FROM `walania_attendance` a
                INNER JOIN `walania_event` e ON a.event_id = e.id
                ORDER BY a.id DESC 
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error in getRecentAttendance: " . $e->getMessage());
            return [];
        }
    }
}