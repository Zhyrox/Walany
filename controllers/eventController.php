<?php
/*
Refactored by: Elmer
*/

session_start();

require_once "../models/eventModel.php";
require_once "../models/Database.php";

$database = new Database();
$dbConnection = $database->getConnection();

$eventModel = new EventModel($dbConnection);


//Handle POST Requests (Create, Update, Delete)
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $name = trim($_POST['name'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $id = $_POST['id'] ?? '';
    $description = trim($_POST['description'] ?? '');

    //Add Event
    if (isset($_POST['add'])) {
        //Input Validation
        if(empty($name) OR empty($event_date) OR empty($location) OR empty($description)){
            //Will add the error next time
        } else {
            $eventModel->addEvent($name, $event_date, $location, $description);
            header("Location: ../views/event.php");
            exit();
        }
    }

    //Update Event
    if (isset($_POST['update'])){
        
        if(empty($id) OR empty($name) OR empty($event_date) OR empty($location) OR empty($description)){
            //Will add the error next time
        } else {
            $eventModel->updateEvent($id, $name, $event_date, $location, $description);
            header("Location: ../views/event.php");
            exit();
        }
    }

    //Delete Event
    if (isset($_POST['delete'])){

        if(empty($id)){
            //Will add the error next time
        } else {
            $eventModel->deleteEvent($id);
            header("Location: ../views/event.php");
            exit();
        }
    }
}
?>