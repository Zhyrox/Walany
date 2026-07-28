<?php
require_once __DIR__ . '/modules/Chatbot/Controllers/ChatController.php';

$action = $_GET['action'] ?? 'index';
$controller = new ChatController();

switch ($action) {
    case 'send':
        $controller->sendMessage();
        break;
    case 'clear':
        $controller->clearChat();
        break;
    case 'get_history':
        $controller->getHistoryApi();
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