<?php
/*
Refactored by: Elmer
*/

session_start();

require_once "../models/registrantModel.php";
require_once "../models/Database.php";

$database = new Database();
$dbConnection = $database->getConnection();

$registrantModel = new RegistrantModel($dbConnection);


//Handle POST Requests (Create, Update, Delete)
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $fullname = trim($_POST['fullname'] ?? '');
    $age = $_POST['age'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $preference_allergy = trim($_POST['preference_allergy'] ?? '');
    $event_id = $_POST['event_id'] ?? '';
    $user_id = $_SESSION['user_id']?? '';
    $id = $_POST['id']?? '';

    //Add Registrant
    if (isset($_POST['add'])) {
        //Input Validation
        if(empty($fullname) OR empty($age) OR empty($email) OR empty($contact_number) OR empty($preference_allergy)){
            //Will add the error next time
        } else {
            $registrantModel->addRegistrant($fullname, $age, $email, $contact_number, $preference_allergy, $event_id, $user_id);
            header("Location: ../views/dashboard.php");
            exit();
        }
    }

    //Update Registrant
    if (isset($_POST['update'])){
        
        if(empty($id) OR empty($fullname) OR empty($age) OR empty($email) OR empty($contact_number) OR empty($preference_allergy) OR empty($event_id)){
            //Will add the error next time
        } else {
            $registrantModel->updateRegistrant($id, $fullname, $age, $email, $contact_number, $preference_allergy, $event_id);
            header("Location: ../views/dashboard.php");
            exit();
        }
    }

    //Delete Registrant
    if (isset($_POST['delete'])){

        if(empty($id)){
            //Will add the error next time
        } else {
            $registrantModel->deleteRegistrant($id);
            header("Location: ../views/dashboard.php");
            exit();
        }
    }
}
?>