<?php
require_once __DIR__ . '/core/config.php';

// 1. Capture the requested module and action from the URL query strings
// Example URL: localhost/walania/index.php?module=registrants&action=register
$module = isset($_GET['module']) ? ucfirst(strtolower($_GET['module'])) : 'Auth';
$action = isset($_GET['action']) ? strtolower($_GET['action']) : 'login';

// 2. Map routing requests to their clean modular directories
switch ($module) {
    case 'Auth':
        // For now, if they want login, route them directly to your login view
        if ($action === 'login') {
            require_once __DIR__ . '/modules/Auth/Views/login.php';
        }
        break;

    case 'Registrants':
        if ($action === 'register') {
            echo "Registrant registration view placeholder";
        }
        break;

    case 'Events':
        if ($action === 'dashboard') {
            echo "Events dashboard view placeholder.";
        }
        break;

    default:
        // Fallback landing page if a module is missing
        echo "404 - Module Architectural Route Not Found.";
        break;
}
?>