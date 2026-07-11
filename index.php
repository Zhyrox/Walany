<?php
require_once __DIR__ . '/modules/Chatbot/Controllers/ChatController.php';

$widgetController = new ChatController();

// 1. Initialize session tokens or state values
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['chat_token'])) {
    $_SESSION['chat_token'] = bin2hex(random_bytes(16));
}

// 2. Fetch required variables that the chat view template expects to find
$dbInstance = new Database();
$chatSessionModel = new ChatSession($dbInstance->getConnection());
$activeSession = $chatSessionModel->getOrCreateSession($_SESSION['chat_token']);

$history = $chatSessionModel->getChatHistory($activeSession['id']);
$suggestions = $widgetController->getSuggestionPrompts();

// 3. Render out the HTML markup cleanly onto the page layer canvas frame
require_once __DIR__ . '/modules/Chatbot/Views/chat-window.php';
?>
<?php
require_once __DIR__ . '/core/config.php';
if (!defined('BASE_URL')) {
    define('BASE_URL', dirname($_SERVER['SCRIPT_NAME']) === '/' ? '/' : dirname($_SERVER['SCRIPT_NAME']) . '/');
}
// 1. Capture the requested module and action from the URL query strings
$module = isset($_GET['module']) ? ucfirst(strtolower($_GET['module'])) : 'Home';
$action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

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

                    // Check the session role and distribute users to their right views
                    if ($_SESSION['role'] === 'admin') {
                        header("Location: /PHP_Project/Walany/index.php?module=Admin&action=view_managers");
                    } elseif ($_SESSION['role'] === 'registrar') {
                        header("Location: /PHP_Project/Walany/index.php?module=Attendance&action=view_events");
                    } elseif ($_SESSION['role'] === 'planner') {
                        header("Location: /PHP_Project/Walany/index.php?module=Events&action=dashboard"); // Modify if you use a different default action
                    } else {
                        // Fallback fallback if an unknown role exists
                        header("Location: /PHP_Project/Walany/index.php?module=Home");
                    }
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

    case 'Attendance':
        require_once __DIR__ . '/modules/Attendance/Controllers/AttendanceController.php';
        $controller = new AttendanceController();

        if ($action === 'view_events') {
            $data = $controller->showEventsList();
            $events = $data['events'] ?? [];
            require_once __DIR__ . '/modules/Attendance/Views/events-list.php';
            exit;
        }
        
        if ($action === 'scanner') {
            $eventId = intval($_GET['event_id'] ?? 0);
            // Grab any existing attendees who checked in earlier before loading view
            $attendees = $controller->getAttendeesList($eventId);
            require_once __DIR__ . '/modules/Attendance/Views/scanner.php';
            exit;
        }

        if ($action === 'process_scan') {
            // New route action catch block to intercept live camera data payloads
            $result = $controller->processAttendanceScan();
            echo json_encode($result);
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