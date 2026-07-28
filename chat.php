<?php
require_once __DIR__ . '/modules/Chatbot/Controllers/ChatController.php';

$action = $_GET['action'] ?? 'index';
$controller = new ChatController();

switch ($action) {
    case 'send':
        $controller->sendMessage();
        break;
    case 'get_active_sessions': // <--- Ensure this case is added!
        $controller->getActiveSessions();
        break;
    case 'clear':
        $controller->clearChat();
        break;
    case 'get_history':
        if (isset($_GET['session_id']) && !empty($_GET['session_id'])) {
            $controller->getHistoryApi(); // Admin looking up specific session
        } else {
            $controller->getUserHistory(); // User widget looking up its active session
        }
        break;
    case 'admin_reply':
        $controller->adminReplyApi();
        break;
    case 'resolve_session':
        $controller->resolveSessionApi();
        break;
    default:
        $controller->index();
        break;
}
?>