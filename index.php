
<?php
require_once __DIR__ . '/core/config.php';
date_default_timezone_set('Asia/Manila');

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
                        header("Location: /Walany/index.php?module=Admin&action=view_managers");
                    } elseif ($_SESSION['role'] === 'registrar') {
                        header("Location: /Walany/index.php?module=Admin&action=registrar_dashboard");
                    } elseif ($_SESSION['role'] === 'planner') {
                        // Point this directly to your admin module routing to avoid directory splitting
                        header("Location: /Walany/index.php?module=Admin&action=planner_dashboard");
                    } else {
                        // Safe fallback if an unknown role is registered
                        header("Location: /Walany/index.php?module=Home");
                    }
                } else {
                    header("Location: /Walany/index.php?module=Auth&action=login&login_error=" . urlencode($response['message']));
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
        $controller->createManager();
        exit;
    }

    if ($action === 'profile_settings') {
        require_once __DIR__ . '/modules/Admin/Views/profile_settings.php';
        exit;
    }

    if ($action === 'update_manager') {
        $res = $controller->updateManager();
        header("Location: /Walany/index.php?module=Admin&action=profile_settings&status=" . $res['status'] . "&message=" . urlencode($res['message']));
        exit;
    }

    if ($action === 'regenerate_key') {
        $managerId = intval($_GET['id'] ?? 0);
        $res = $controller->regenerateTempPassword($managerId);
        header("Location: /Walany/index.php?module=Admin&action=view_managers&status=" . $res['status'] . "&message=" . urlencode($res['message']));
        exit;
    }

    if ($action === 'system_error') {
        require_once __DIR__ . '/modules/Admin/Views/error-landing.php';
        exit;
    }

    if ($action === 'registrar_dashboard') {
        require_once 'modules/Admin/Controllers/RegistrarController.php';
        $controller = new RegistrarController();
        $controller->registrarDashboard();
        exit();
    }

    if ($action === 'planner_dashboard') {
        require_once 'modules/Admin/Controllers/PlannerController.php';
        $controller = new PlannerController();
        $controller->plannerDashboard();
        exit();
    }

    // EVENT CRUD ACTIONS FOR PLANNER CONTROLLER
    if ($action === 'createevent') {
        require_once 'modules/Admin/Controllers/PlannerController.php';
        $controller = new PlannerController();
        $controller->createEvent();
        exit();
    }

    if ($action === 'editevent') {
        require_once 'modules/Admin/Controllers/PlannerController.php';
        $controller = new PlannerController();
        $controller->editEvent();
        exit();
    }

    if ($action === 'deleteevent') {
        require_once 'modules/Admin/Controllers/PlannerController.php';
        $controller = new PlannerController();
        $controller->deleteEvent();
        exit();
    }

    if ($action === 'getlivelogsapi') {
        require_once 'modules/Admin/Controllers/PlannerController.php';
        $controller = new PlannerController();
        $controller->getLiveLogsApi();
        exit();
    }

    if ($action === 'exportguestlist') {
        require_once 'modules/Admin/Controllers/PlannerController.php';
        $controller = new PlannerController();
        $controller->exportGuestList();
        exit();
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
            exit;
        }
        
        if ($action === 'submit_registration') {
            $result = $controller->handleRegistration();
            echo json_encode($result);
            exit;
        }
        
        if ($action === 'verify_otp') {
            $result = $controller->verifyOTP();
            echo json_encode($result); // Correctly spits out the JSON for JS to read
            exit;
        }

        if ($action === 'process_payment') {
            $controller->redirectToPayMongoCheckout();
            exit;
        }

        if ($action === 'payment_callback') {
            require_once __DIR__ . '/modules/Registrants/Views/payment-callback.php';
            exit;
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
        require_once __DIR__ . '/modules/Homepage/Views/homepage.php';
        break;
}
?>