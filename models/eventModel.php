<?php
require_once "db.php";

function getAllEvents() {
    $pdo = walania_db();
    $stmt = $pdo->query("SELECT * FROM walania_event");
    return $stmt->fetchAll();
}

function addEvent($name, $event_date, $location, $description) {
    $pdo = walania_db();
    $stmt = $pdo->prepare("INSERT INTO walania_event (name, event_date, location, description) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $event_date, $location, $description]);
}

function updateEvent($id, $name, $event_date, $location, $description) {
    $pdo = walania_db();
    $stmt = $pdo->prepare("UPDATE walania_event SET name=?, event_date=?, location=?, description=? WHERE id=?");
    return $stmt->execute([$name, $event_date, $location, $description, $id]);
}

function deleteEvent($id) {
    $pdo = walania_db();
    $stmt = $pdo->prepare("DELETE FROM walania_event WHERE id=?");
    return $stmt->execute([$id]);
}

?>