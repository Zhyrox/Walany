<?php
class Attendance {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addAttendance($registrant_id, $event_id){
        $stmt = $this->db->prepare("INSERT INTO walania_attendance (registrant_id, event_id) VALUES (?, ?)");
        return $stmt->execute([$registrant_id, $event_id]);
    }

    public function getAttendance(){
        $stmt = $this->db->query("SELECT * FROM walania_attendance");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAttendance($attendance_status, $registrant_id){
        $stmt = $this->db->prepare("UPDATE walania_attendance SET attendance_status = ? WHERE registrant_id = ?");
        $stmt->execute([$attendance_status, $registrant_id]);
    }
}
?>