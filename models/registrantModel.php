<?php
require_once "db.php";

function getAllRegistrants() {
    $pdo = walania_db();
    $stmt = $pdo->query("SELECT * FROM walania_registrant");
    return $stmt->fetchAll();
}

function addRegistrant($fullname, $age, $email, $contact_number, $preference_allergy, $event_id, $user_id) {
    $pdo = walania_db();
    $stmt = $pdo->prepare("INSERT INTO walania_registrant (fullname, age, email, contact_number, preference_allergy, event_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$fullname, $age, $email, $contact_number, $preference_allergy, $event_id, $user_id]);
}

function updateRegistrant($id, $fullname, $age, $email, $contact_number, $preference_allergy, $event_id) {
    $pdo = walania_db();
    $stmt = $pdo->prepare("UPDATE walania_registrant SET fullname=?, age=?, email=?, contact_number=?, preference_allergy=?, event_id=? WHERE id=?");
    return $stmt->execute([$fullname, $age, $email, $contact_number, $preference_allergy, $event_id, $id]);
}

function deleteRegistrant($id) {
    $pdo = walania_db();
    $stmt = $pdo->prepare("DELETE FROM walania_registrant WHERE id=?");
    return $stmt->execute([$id]);
}

?>