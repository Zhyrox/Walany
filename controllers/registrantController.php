<?php 
require_once "../models/registrantModel.php";

if (isset($_POST['add'])) {
    addRegistrant($_POST['fullname'], $_POST['age'], $_POST['email'], $_POST['contact_number'], $_POST['preference_allergy'], $_POST['event_id'], $_SESSION['user_id']);
    header("Location: ../views/dashboard.php");
    exit();
}

if (isset($_POST['update'])) {
    updateRegistrant($_POST['id'], $_POST['fullname'], $_POST['age'], $_POST['email'], $_POST['contact_number'], $_POST['preference_allergy'], $_POST['event_id']);
    header("Location: ../views/dashboard.php");
    exit();
}

if (isset($_GET['delete'])) {
    deleteRegistrant($_GET['delete']);
    header("Location: ../views/dashboard.php");
    exit();
}
?>