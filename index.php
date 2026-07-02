<?php
require_once __DIR__ . '/core/config.php';

// 1. Capture the requested module and action from the URL query strings
// Example URL: localhost/walania/index.php?module=registrants&action=register
$module = isset($_GET['module']) ? ucfirst(strtolower($_GET['module'])) : 'Auth';    // 1. Auth 2.Registrants 3. Events 4. Home
$action = isset($_GET['action']) ? strtolower($_GET['action']) : 'login';                // 1. login 2. register 3. evaluate

// 2. Map routing requests to their clean modular directories
switch ($module) {
    case 'Auth':
        //require_once __DIR__ . '/modules/Auth/Controllers/AuthController.php';
        //$controller = new AuthController();

        if ($action === 'login') {
            require_once __DIR__ . '/modules/Auth/Views/login.php';
        }
        break;

    case 'Home':
        require_once __DIR__ . '/modules/Home/Views/landing.php';
        break;

    case 'Registrants':
        require_once __DIR__ . '/modules/Registrants/Controllers/RegistrantController.php';
        $controller = new RegistrantController();
        
        if ($action === 'register') {
            require_once __DIR__ . '/modules/Registrants/Views/register.php';
        }
        elseif ($action === 'submit_registration') {
            $result = $controller->handleRegistration();
            echo json_encode($result);
        }
        break;

    case 'Events':
        require_once __DIR__ . '/modules/Events/Controllers/EventController.php';
        $controller = new EventController();

        if ($action === 'evaluate') {
            require_once __DIR__ . '/modules/Events/Views/evaluate.php';
        }
        elseif ($action === 'submit_evaluation') {
            $result = $controller->handleEvaluation();
            echo json_encode($result);
        }
        break;
        
    default:
        require_once __DIR__ . '/modules/Home/Views/landing.php';
        break;
}
?>