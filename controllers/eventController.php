<?php 
require_once "../models/eventModel.php";
    
if (isset($_POST['add'])) {
    addEvent($_POST['name'], $_POST['event_date'], $_POST['location'], $_POST['description']);
    header("Location: ../views/event.php");
    exit();
}

if (isset($_POST['update'])) {
    updateEvent($_POST['id'], $_POST['name'], $_POST['event_date'], $_POST['location'], $_POST['description']);
    header("Location: ../views/event.php");
    exit();
}

if (isset($_GET['delete'])) {
    deleteEvent($_GET['delete']);
    header("Location: ../views/event.php");
    exit();
}
?>