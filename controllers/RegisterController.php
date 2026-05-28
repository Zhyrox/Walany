<?php
/*
Register Controller
Author: Elmer
*/
session_start();

require_once "../models/Database.php"; // Database Model
require_once "../models/UserModel.php";// User Model

$database = new Database();
$dbConnection = $database->getConnection();

//Validate Request Method
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $_SESSION['namepass_error'] = '';
    $_SESSION['accexist_error'] = '';

    if (empty($username) || empty($password)) {

        $_SESSION['namepass_error'] = 'Username and Password are required.';

        header("Location: ../views/register.php");
        exit();
    }
    
    $userModel = new UserModel($dbConnection); //Calls user model
    $exists = $userModel->usernameExists($username);

    if($exists){
        
        $_SESSION['accexist_error'] = 'Account already exists.';

        header("Location: ../views/register.php");
        exit();
    }

    $userModel->registerUser($username, $password);

    header("Location: ../views/login.php");
    exit();
}


?>