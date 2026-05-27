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
        $stmt = $this->db->query("SELECT * FROM walania_registrant");
        return $stmt->fetchA;;(PDO::FETCH_ASSOC);
    }

    public function addRegistrant($fullname, $age, $email, $contact_number, $preference_allergy, $event_id, $user_id){
        $stmt = $this->db->prepare("INSERT INTO walania_registrant (fullname, age, email, contact_number, preference_allergy, event_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$fullname, $age, $email, $contact_number, $preference_allergy, $event_id, $user_id]);
    }

    public function updateRegistrant($id, $fullname, $age, $email, $contact_number, $preference_allergy, $event_id){
        $stmt = $this->db->prepare("UPDATE walania_registrant SET fullname=?, age=?, email=?, contact_number=?, preference_allergy=?, event_id=? WHERE id=?");
        return $stmt->execute([$fullname, $age, $email, $contact_number, $preference_allergy, $event_id, $id]);
    }

    public function deleteRegistrant($id){
        $stmt = $this->db->prepare("DELETE FROM walania_registrant WHERE id=?");
        return $stmt->execute([$id]);
    }

}
?>