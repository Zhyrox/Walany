<?php
class EventModel{

    private $db;

    public function __construct($dbConnection){
        $this->db = $dbConnection;
    }

    public function getAllEvents(){
        $stmt = $this->db->query("SELECT * FROM walania_event");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addEvent($name, $event_date, $location, $description){
        $stmt = $this->db->prepare("INSERT INTO walania_event (name, event_date, location, description) VALUES (:name, :event_date, :location, :description)");

        return $stmt->execute([
            'name'        => $name,
            'event_date'  => $event_date,
            'location'    => $location,
            'description' => $description
            ]);
    }

    public function updateEvent($id, $name, $event_date, $location, $description){
        $stmt = $this->db->prepare("UPDATE walania_event SET name=:name, event_date=:event_date, location=:location, description=:description WHERE id=:id");
        
        return $stmt->execute([
            'name'        => $name,
            'event_date'  => $event_date,
            'location'    => $location,
            'description' => $description,
            'id'          => $id
            ]);
    }

    public function deleteEvent($id){
        $stmt = $this->db->prepare("DELETE FROM walania_event WHERE id=:id");
        return $stmt->execute(['id' => $id]);
    }
}
?>