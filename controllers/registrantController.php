<?php
/*
Refactored by: Elmer (Pake ko, agaw credits lng boss)
*/

session_start();

require_once "../models/registrantModel.php";
require_once "../models/Database.php";
require_once "../models/attendanceModel.php";

$database = new Database();
$dbConnection = $database->getConnection();

$registrantModel = new RegistrantModel($dbConnection);
$attendanceModel = new Attendance($dbConnection);


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
        if(empty($fullname) OR empty($age) OR empty($email) OR empty($contact_number) OR empty($event_id)){
            //Will add the error next time
        } else {
            $registrant_id = $registrantModel->addRegistrant($fullname, $age, $email, $contact_number, $preference_allergy, $event_id, $user_id);
            //added attendance entry for the new registrant, due to the action accpeting one url only
            $attendanceModel->addAttendance($registrant_id, $event_id);
            header("Location: ../views/registrant.php");
            exit();
        }
    }

    //Update Registrant
    if (isset($_POST['update'])){
        
        if(empty($id) OR empty($fullname) OR empty($age) OR empty($email) OR empty($contact_number) OR empty($event_id)){
            //Will add the error next time
        } else {
            $registrantModel->updateRegistrant($id, $fullname, $age, $email, $contact_number, $preference_allergy, $event_id);
            header("Location: ../views/registrant.php");
            exit();
        }
    }

    //Attendance Update, due to this being the only function in an attendance controller. I've taken the liberty to add it here
    //Since it also accepts the same url as the other functions, and it would be a hassle to create another controller just for this.

    if (isset($_POST['attendance_update'])){
        $id = $_POST['registrant_id'] ?? null;
        $attendance_status = $_POST['attendance_status'] ?? null;

        if(empty($id) OR empty($attendance_status)){
            //Will add the error next time
        } else {
            $attendanceModel->updateAttendance($attendance_status, $id);
            header("Location: ../views/registrant.php");
            exit();
        }
    }

    //Delete Registrant
    if (isset($_POST['delete'])){

        if(empty($id)){
            //Will add the error next time
        } else {
            $registrantModel->deleteRegistrant($id);
            header("Location: ../views/registrant.php");
            exit();
        }
    }
}
?>