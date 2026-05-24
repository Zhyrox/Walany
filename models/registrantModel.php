<?php
require_once "db.php";

function getAllRegistrants() {
    $pdo = walania_db();
    $stmt = $pdo->query("SELECT * FROM registrants");
    return $stmt->fetchAll();
}

function addRegistrant($name, $email, $phone, $preference) {
    $pdo = walania_db();
    $stmt = $pdo->prepare("INSERT INTO registrants (name, email, phone, preference) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $email, $phone, $preference]);
}

function updateRegistrant($id, $name, $email, $phone, $preference) {
    $pdo = walania_db();
    $stmt = $pdo->prepare("UPDATE registrants SET name=?, email=?, phone=?, preference=? WHERE id=?");
    return $stmt->execute([$name, $email, $phone, $preference, $id]);
}

function deleteRegistrant($id) {
    $pdo = walania_db();
    $stmt = $pdo->prepare("DELETE FROM registrants WHERE id=?");
    return $stmt->execute([$id]);
}

?>