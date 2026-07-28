<?php
require_once __DIR__ . '/../Controllers/ChatController.php';
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../Models/ChatSessions.php';

$widgetController = new ChatController();

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['chat_token'])) {
    $_SESSION['chat_token'] = bin2hex(random_bytes(16));
}

$dbInstance = new Database();
$chatSessionModel = new ChatSession($dbInstance->getConnection());
$activeSession = $chatSessionModel->getOrCreateSession($_SESSION['chat_token']);

// Revert session back to 'bot' on page load/refresh
if ($activeSession['status'] === 'human') {
    $chatSessionModel->updateSessionStatus($activeSession['id'], 'bot');
    $activeSession['status'] = 'bot';
}

$history = $chatSessionModel->getChatHistory($activeSession['id']);
$suggestions = $widgetController->getSuggestionPrompts();

require __DIR__ . '/chat-window.php';
?>