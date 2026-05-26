<?php
//Auth Controller

session_start();

require_once "model-drafts.php";

$database = new Database();
$dbConnection = $database->getConnection();

//Validate Request Method
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    //Get Username & Password
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Input Validation
    if(empty($username) OR empty($password)){
        header("Location: ../views/login.php");
        exit();
    } else {
        
        $userModel = new UserModel($dbConnection); //Calls user model
        $userData = $userModel->loginUser($username, $password);

        if($userData){
            $_SESSION['user'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];

            header("Location: ../views/dashboard.php");
            exit();
        } else {
            header("Location: ../views/login.php");
            exit();
        }

    }
}
?>