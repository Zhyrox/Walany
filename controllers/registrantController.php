<?php
require_once "../models/registrantModel.php";

function listRegistrants() {
    $registrants = getAllRegistrants();
    include "../views/registrantList.php";
}

function createRegistrant($data) {
    $success = addRegistrant($data['name'], $data['email'], $data['phone'], $data['preference']);
    if ($success) {
        header("Location: /registrants");
        exit();
    } else {
        include "../views/registrantForm.php";
    }
}

function editRegistrant($id, $data) {
    $success = updateRegistrant($id, $data['name'], $data['email'], $data['phone'], $data['preference']);
    if ($success) {
        header("Location: /registrants");
        exit();
    } else {
        include "../views/registrantForm.php";
    }
}

function deleteRegistrant($id) {
    $success = deleteRegistrant($id);
    header("Location: /registrants");
    exit();
}

?>