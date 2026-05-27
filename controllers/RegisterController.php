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

    if (empty($username) || empty($password)) {

        header("Location: ../views/register.php");
        exit();
    }
    
    $userModel = new UserModel($dbConnection); //Calls user model
    $exists = $userModel->usernameExists($username);

    if($exists){
        //add error later
        header("Location: ../views/register.php");
        exit();
    }

    $userModel->registerUser($username, $password);

    header("Location: ../views/login.php");
    exit();
}


?>