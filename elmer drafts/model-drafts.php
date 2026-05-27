<?php
//author @elmer - Lahat ng babaguhin pasabi sakin

//UserModel.php


//RegistrantModel.php
class RegistrantModel{

}

//EventModel.php

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
        $stmt = $this->db->prepare("INSERT INTO walania_event (name, event_date, location, description) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $event_date, $location, $description]);
    }

    public function updateEvent($id, $name, $event_date, $location, $description){
        $stmt = $this->db->prepare("UPDATE walania_event SET name=?, event_date=?, location=?, description=? WHERE id=?");
        return $stmt->execute([$name, $event_date, $location, $description, $id]);
    }

    public function deleteEvent($id){
        $stmt = $this->db->prepare("DELETE FROM walania_event WHERE id=?");
        return $stmt->execute([$id]);
    }
}


//Db.php
