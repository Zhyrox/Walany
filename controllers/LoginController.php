<?php
/*
Login Controller
Author: Elmer
*/

session_start();

require_once "../models/Database.php"; // Database Model
require_once "../models/UserModel.php";// User Model

$database = new Database();
$dbConnection = $database->getConnection();

//Validate Request Method
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    //Get Username & Password
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $_SESSION['namepass_error'] = '';
    $_SESSION['login_error'] = '';

    //Input Validation
    if(empty($username) OR empty($password)){

        $_SESSION['namepass_error'] = 'Username and Password are required.';

        header("Location: ../views/login.php");
        exit();
    } else {
        
        $userModel = new UserModel($dbConnection); //Calls user model
        $userData = $userModel->loginUser($username, $password);

        if($userData){
        session_regenerate_id(true);

        $_SESSION['user_id'] = $userData['id'] ?? null;
        $_SESSION['username'] = $userData['username'] ?? '';

            header("Location: ../views/dashboard.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid Username or Password.";
            header("Location: ../views/login.php");
            exit();
        }

    }
}
?>