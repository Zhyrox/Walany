<?php
require_once "db.php";

function getAllEvents() {
    $pdo = walania_db();
    $stmt = $pdo->query("SELECT * FROM walaniaevent");
    return $stmt->fetchAll();
}

function addEvent($name, $date, $location, $description) {
    $pdo = walania_db();
    $stmt = $pdo->prepare("INSERT INTO walaniaevent (name, date, location, description) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $date, $location, $description]);
}

function updateEvent($id, $name, $date, $location, $description) {
    $pdo = walania_db();
    $stmt = $pdo->prepare("UPDATE walaniaevent SET name=?, date=?, location=?, description=? WHERE id=?");
    return $stmt->execute([$name, $date, $location, $description, $id]);
}

function deleteEvent($id) {
    $pdo = walania_db();
    $stmt = $pdo->prepare("DELETE FROM walaniaevent WHERE id=?");
    return $stmt->execute([$id]);
}

?>