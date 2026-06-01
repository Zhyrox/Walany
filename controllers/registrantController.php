<?php
session_start();

require_once "../models/registrantModel.php";
require_once "../models/Database.php";
require_once "../models/attendanceModel.php";

$database = new Database();
$dbConnection = $database->getConnection();

$registrantModel = new RegistrantModel($dbConnection);
$attendanceModel = new Attendance($dbConnection);

// Handle POST Requests (Create, Update, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. ADD REGISTRANT
    if (isset($_POST['add'])) {
        $fullname = trim($_POST['fullname'] ?? '');
        $age = $_POST['age'] ?? '';
        $email = trim($_POST['email'] ?? '');
        $contact_number = trim($_POST['contact_number'] ?? '');
        $preference_allergy = trim($_POST['preference_allergy'] ?? '');
        $event_id = $_POST['event_id'] ?? '';
        
        // Security Check: Kick them out or show an error if session expired
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            die("ERROR: Unauthorized access. Please log in again.");
        }

        if (empty($fullname) || empty($age) || empty($email) || empty($contact_number) || empty($event_id)) {
            echo "ERROR";
        } else {
            $registrant_id = $registrantModel->addRegistrant($fullname, $age, $email, $contact_number, $preference_allergy, $event_id, $user_id);
            $attendanceModel->addAttendance($registrant_id, $event_id);
            header("Location: ../views/registrant.php");
            exit();
        }
    }

    // 2. UPDATE REGISTRANT
    if (isset($_POST['update'])) {
        $id = $_POST['id'] ?? '';
        $fullname = trim($_POST['fullname'] ?? '');
        $age = $_POST['age'] ?? '';
        $email = trim($_POST['email'] ?? '');
        $contact_number = trim($_POST['contact_number'] ?? '');
        $preference_allergy = trim($_POST['preference_allergy'] ?? '');
        $event_id = $_POST['event_id'] ?? '';

        if (empty($id) || empty($fullname) || empty($age) || empty($email) || empty($contact_number) || empty($event_id)) {
            echo "ERROR";
        } else {
            $registrantModel->updateRegistrant($id, $fullname, $age, $email, $contact_number, $preference_allergy, $event_id);
            header("Location: ../views/registrant.php");
            exit();
        }
    }

    // 3. ATTENDANCE UPDATE
    if (isset($_POST['attendance_update'])) {
        $id = $_POST['registrant_id'] ?? null;
        $attendance_status = $_POST['attendance_status'] ?? null;

        if (empty($id) || $attendance_status === '') {
            echo "ERROR";
        } else {
            $attendanceModel->updateAttendance($attendance_status, $id);
            header("Location: ../views/registrant.php");
            exit();
        }
    }

    // 4. DELETE REGISTRANT
    if (isset($_POST['delete'])) {
        // Safe Fallback: Checks both 'id' and 'registrant_id' formats depending on your frontend form setup
        $id = $_POST['id'] ?? $_POST['registrant_id'] ?? '';

        if (empty($id)) {
            echo "ERROR: Missing ID for deletion";
        } else {
            $registrantModel->deleteRegistrant($id);
            header("Location: ../views/registrant.php");
            exit();
        }
    }
}
?>