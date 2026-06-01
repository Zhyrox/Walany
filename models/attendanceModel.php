<?php
class Attendance {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    }

    public function addAttendance($registrant_id, $event_id){
        $stmt = $this->db->prepare("INSERT INTO walania_attendance (registrant_id, event_id) VALUES (:registrant_id, :event_id)");
        
        return $stmt->execute([
            'registrant_id' => $registrant_id,
            'event_id' => $event_id
            ]);
    }

    public function getAttendance(){
        $stmt = $this->db->query("SELECT * FROM walania_attendance");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAttendance($attendance_status, $registrant_id){
        $stmt = $this->db->prepare("UPDATE walania_attendance
                                        SET attendance_status = :attendance_status,
                                            time_checked_in = CASE
                                            WHEN :attendance_status IN ('late', 'present') THEN NOW()
                                            WHEN :attendance_status IN ('absent' , 'n/a') THEN NULL
                                            ELSE time_checked_in
                                        END
                                    WHERE registrant_id = :registrant_id");

        $stmt->execute([
            'attendance_status' => $attendance_status,
            'registrant_id' => $registrant_id
            ]);
    }
}
?>