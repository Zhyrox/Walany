<?php
require_once __DIR__ . '/core/config.php';

// 1. Capture the requested module and action from the URL query strings
// Example URL: localhost/walania/index.php?module=registrants&action=register
$module = isset($_GET['module']) ? ucfirst(strtolower($_GET['module'])) : 'Home';    // 1. Auth 2.Registrants 3. Events 4. Home
$action = isset($_GET['action']) ? strtolower($_GET['action']) : '';                // 1. login 2. register 3. evaluate

// 2. Map routing requests to their clean modular directories
switch ($module) {
    case 'Auth':
        require_once __DIR__ . '/modules/Auth/Controllers/AuthController.php';
        $controller = new AuthController();
        
        if ($action === 'login') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Process the login submission
                $response = $controller->handleLogin();
                if ($response['status'] === 'success') {
                    header("Location: /PHP_Project/Walany/index.php?module=Admin&action=view_managers");
                } else {
                    header("Location: /PHP_Project/Walany/index.php?module=Auth&action=login&login_error=" . urlencode($response['message']));
                }
                exit;
            } else {
                // Just display the login view page if it's a GET request
                require_once __DIR__ . '/modules/Auth/Views/login.php';
                exit;
            }
        }
        break;

    case 'Admin':
        require_once __DIR__ . '/modules/Admin/Controllers/ManagerController.php';
        $controller = new ManagerController();
        
        // Wrap rendering the views inside an action case
        if ($action === 'view_managers') {
            require_once __DIR__ . '/modules/Admin/Views/managers.php';
            exit;
        }
        
        if ($action === 'create_manager') {
            $res = $controller->createManager();
            header("Location: /PHP_Project/Walany/index.php?module=Admin&action=view_managers&status=" . $res['status'] . "&message=" . urlencode($res['message']));
            exit;
        }
        
        if ($action === 'update_manager') {
            $managerId = intval($_POST['manager_id'] ?? 0);
            $res = $controller->updateManager($managerId);
            header("Location: /PHP_Project/Walany/index.php?module=Admin&action=view_managers&status=" . $res['status'] . "&message=" . urlencode($res['message']));
            exit;
        }
        break;

    case 'Home':

        require_once __DIR__ . '/modules/Homepage/Controllers/HomepageController.php';

        $controller = new HomepageController();
        $controller->index();
        
        require_once __DIR__ . '/modules/Homepage/Views/homepage.php';
        
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