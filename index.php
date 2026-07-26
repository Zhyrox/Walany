<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
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
        include_once __DIR__ . '/modules/Chatbot/Views/render-widget.php';
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
        // Normalize action to lowercase to avoid case-sensitivity issues
        $currentAction = strtolower($action);

        require_once __DIR__ . '/modules/Admin/Controllers/ManagerController.php';
        $controller = new ManagerController();

        if ($currentAction === 'view_managers') {
            require_once __DIR__ . '/modules/Admin/Views/managers.php';
            exit;
        }

        if ($currentAction === 'create_manager') {
            $controller->createManager();
            exit;
        }

        if ($currentAction === 'profile_settings') {
            require_once __DIR__ . '/modules/Admin/Views/profile_settings.php';
            exit;
        }

        if ($currentAction === 'update_manager') {
            $res = $controller->updateManager();
            header("Location: /Walany/index.php?module=Admin&action=profile_settings&status=" . $res['status'] . "&message=" . urlencode($res['message']));
            exit;
        }

        if ($currentAction === 'regenerate_key') {
            $managerId = intval($_GET['id'] ?? 0);
            $res = $controller->regenerateTempPassword($managerId);
            header("Location: /Walany/index.php?module=Admin&action=view_managers&status=" . $res['status'] . "&message=" . urlencode($res['message']));
            exit;
        }

        if ($currentAction === 'system_error') {
            require_once __DIR__ . '/modules/Admin/Views/error-landing.php';
            exit;
        }

        if ($currentAction === 'registrar_dashboard') {
            require_once 'modules/Admin/Controllers/RegistrarController.php';
            $regController = new RegistrarController();
            $regController->registrarDashboard();
            exit();
        }

        if ($currentAction === 'planner_dashboard' || $currentAction === 'plannerdashboard') {
            require_once 'modules/Admin/Controllers/PlannerController.php';
            $planner = new PlannerController();
            $planner->plannerDashboard();
            exit();
        }

        // --- PLANNER / EVENT ACTIONS ---
        if (in_array($currentAction, ['createevent', 'editevent', 'archiveevent', 'unarchiveevent', 'toggleregistration', 'getlivelogsapi', 'exportguestlist'])) {
            require_once 'modules/Admin/Controllers/PlannerController.php';
            $planner = new PlannerController();

            if ($currentAction === 'createevent') {
                $planner->createEvent();
                exit();
            }

            if ($currentAction === 'editevent') {
                $planner->editEvent();
                exit();
            }

            if ($currentAction === 'archiveevent') {
                $planner->archiveEvent();
                exit();
            }

            if ($currentAction === 'unarchiveevent') {
                $planner->unarchiveEvent();
                exit();
            }

            if ($currentAction === 'toggleregistration') {
                $planner->toggleRegistration();
                exit();
            }

            if ($currentAction === 'getlivelogsapi') {
                $planner->getLiveLogsApi();
                exit();
            }

            if ($currentAction === 'exportguestlist') {
                $planner->exportGuestList();
                exit();
            }
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
            $controller->showEvaluationForm();
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


if ($action === 'admin_reply') {
    $input = json_decode(file_get_contents('php://input'), true);
    // Notice sender name becomes 'agent' so customer view changes layout borders to distinguish real human help
    $chatSessionModel->saveMessage($input['session_id'], 'agent', $input['message']);
    echo json_encode(['status' => 'success']);
    exit;
}

if ($action === 'resolve_session') {
    $input = json_decode(file_get_contents('php://input'), true);
    $chatSessionModel->updateSessionStatus($input['session_id'], 'bot');
    echo json_encode(['status' => 'success']);
    exit;
}
?>
    <?php include_once __DIR__ . '/modules/Chatbot/Views/render-widget.php'; ?>
<?php ob_end_flush(); ?>
