<?php
/*

Author: Elmer

*/
class RegistrantModel{

    private $db;

    public function __construct($dbConnection){
        $this->db = $dbConnection;
    }

    public function getAllRegistrants(){
        // Include attendance_status from walania_attendance so the view can show current status
        $stmt = $this->db->query(
            "SELECT r.*, e.name AS event_name, a.attendance_status
             FROM walania_registrant r
             LEFT JOIN walania_event e ON r.event_id = e.id
             LEFT JOIN walania_attendance a ON a.registrant_id = r.id"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addRegistrant($fullname, $age, $email, $contact_number, $preference_allergy, $event_id, $user_id){
        $stmt = $this->db->prepare("INSERT INTO walania_registrant (full_name, age, email, contact_number, preference_allergy, event_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fullname, $age, $email, $contact_number, $preference_allergy, $event_id, $user_id]);
        //return the last inserted id for the attendance entry
        return $this->db->lastInsertId();
    }

    public function updateRegistrant($id, $fullname, $age, $email, $contact_number, $preference_allergy, $event_id){
        $stmt = $this->db->prepare("UPDATE walania_registrant SET full_name=?, age=?, email=?, contact_number=?, preference_allergy=?, event_id=? WHERE id=?");
        return $stmt->execute([$fullname, $age, $email, $contact_number, $preference_allergy, $event_id, $id]);
    }

    public function deleteRegistrant($id){
        $stmt = $this->db->prepare("DELETE FROM walania_registrant WHERE id=?");
        return $stmt->execute([$id]);
    }

}
?>