<?php
require_once "db.php";

function registerUser($username, $password) {
    $pdo = walania_db();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO walania_user (username, password) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$username, $hashedPassword]);
}

function loginUser($username) {
    $pdo = walania_db();
    $sql = "SELECT * FROM walania_user WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function getUserById($id) {
    $pdo = walania_db();
    $sql = "SELECT * FROM walania_user WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}
?>