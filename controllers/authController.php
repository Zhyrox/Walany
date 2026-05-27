<?php

session_start();

require_once "../models/userModel.php";

//test user: hunter password: 111

// username: admin -> password: admin123
// password: admin123 -> hash: $2y$10$tVAv5pLY4aSTnsYzaL1Ng.PuOXz61gu4f/ER.EjNEA9T3xP0dJhG6

if (isset($_POST['register'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    registerUser($username, $password);

    header("Location: ../views/login.php");
    exit();
}

if (isset($_POST['login'])) {

    $username = trim((string)($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';

    $_SESSION['namepass_error'] = '';
    $_SESSION['login_error'] = '';

    if ($username === '' || $password === '') {
        $_SESSION['namepass_error'] = 'Username and Password are required.';
        header("Location: ../views/login.php");
        exit();
    }

    $user = loginUser($username);

    if (!$user || !is_array($user)) {
        $_SESSION['login_error'] = "Invalid Username or Password.";
        header("Location: ../views/login.php");
        exit;
    }

    // support either column name: 'password' or 'password_hash'
    $storedHash = $user['password'] ?? $user['password_hash'] ?? null;

    if ($storedHash === null) {
        error_log('Login failed: no password field for user ' . $username);
        $_SESSION['login_error'] = "Invalid Username or Password.";
        header("Location: ../views/login.php");
        exit;
    }

    if (password_verify($password, $storedHash)) {
        session_regenerate_id(true);
        // set user_id so dashboard.php check passes; support both possible column names
        $_SESSION['user_id'] = $user['id'] ?? $user['user_id'] ?? null;
        $_SESSION['username'] = $user['username'] ?? $user['email'] ?? '';

        header("Location: ../views/dashboard.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Invalid Username or Password.";
        header("Location: ../views/login.php");
        exit;
    }
}
?>