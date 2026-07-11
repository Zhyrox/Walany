<?php
// C:\xampp\htdocs\Walany\chat.php
require_once __DIR__ . '/modules/Chatbot/Controllers/ChatController.php';

$controller = new ChatController();

// Check if JavaScript is calling the JSON response action endpoint
if (isset($_GET['action']) && $_GET['action'] === 'send') {
    $controller->sendMessage();
} else {
    // Otherwise, standard browser page load maps the index template view with $history
    $controller->index();
}
?>