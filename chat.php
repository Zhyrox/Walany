<?php
require_once __DIR__ . '/modules/Chatbot/Controllers/ChatController.php';

$action = $_GET['action'] ?? 'index';
$controller = new ChatController();

if ($action === 'send') {
    $controller->sendMessage();
} elseif ($action === 'clear') {
    $controller->clearChat();
} else {
    $controller->index();
}
?>