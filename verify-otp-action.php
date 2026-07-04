<?php
// verify-otp-action.php

// 1. Load your controller file (Adjust path if needed to point to your controller)
require_once __DIR__ . '/modules/Controllers/RegistrantController.php';

// 2. Instantiate the class
$controller = new RegistrantController();

// 3. Call the method and echo back the JSON response to your JavaScript form
header('Content-Type: application/json');
echo json_encode($controller->verifyOTP());
exit();
?>