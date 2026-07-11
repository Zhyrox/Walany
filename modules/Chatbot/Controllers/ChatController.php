<?php
// C:\xampp\htdocs\Walany\modules\Chatbot\Controllers\ChatController.php
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../../../core/Config.php';
require_once __DIR__ . '/../Models/ChatSessions.php';

class ChatController {
    private $chatModel;
    private $apiKey = GEMINI_CHATBOT_KEY; // Provide your valid token key element

    public function __construct() {
        $dbInstance = new Database();
        $this->chatModel = new ChatSession($dbInstance->getConnection());
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['chat_token'])) {
            $_SESSION['chat_token'] = bin2hex(random_bytes(16));
        }

        $session = $this->chatModel->getOrCreateSession($_SESSION['chat_token']);
        $history = $this->chatModel->getChatHistory($session['id']);
        
        // Render target layout file securely
        require_once __DIR__ . '/../Views/chat-window.php';
    }

    public function sendMessage() {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        $token = $_SESSION['chat_token'] ?? '';
        $input = json_decode(file_get_contents('php://input'), true);
        $userMsg = trim($input['message'] ?? '');

        if (empty($token) || empty($userMsg)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data payload']);
            exit;
        }

        $session = $this->chatModel->getOrCreateSession($token);
        $this->chatModel->saveMessage($session['id'], 'user', $userMsg);

        // 1. Human Takeover Escalation Logic (Persistent Check)
        if ($session['status'] === 'human' || stripos($userMsg, 'talk to human') !== false || stripos($userMsg, 'agent') !== false) {
            if ($session['status'] !== 'human') {
                $this->chatModel->requestHumanTakeover($session['id']);
            }
            $reply = "I am transferring your session to a live campus coordinator. They will review this conversation thread shortly.";
            $this->chatModel->saveMessage($session['id'], 'agent', $reply);
            echo json_encode(['status' => 'success', 'reply' => $reply, 'mode' => 'human']);
            exit;
        }

        // 2. Local FAQ Layer
        $faqReply = $this->checkLocalFAQs($userMsg);
        if ($faqReply) {
            $this->chatModel->saveMessage($session['id'], 'bot', $faqReply);
            echo json_encode(['status' => 'success', 'reply' => $faqReply, 'mode' => 'bot']);
            exit;
        }

        // 3. Contextual Multi-Turn Call down to Gemini Model Core
        $botReply = $this->fetchGeminiResponse($userMsg, $session['id']);
        $this->chatModel->saveMessage($session['id'], 'bot', $botReply);
        
        echo json_encode(['status' => 'success', 'reply' => $botReply, 'mode' => 'bot']);
        exit;
    }

    private function checkLocalFAQs($msg) {
        $msg = strtolower($msg);
        $faqs = [
            'how to register' => 'To register for events, go to our home screen dashboard, locate your target event card, and click the blue "Register Now" button.',
            'give feedback'   => 'You can provide event feedback evaluations by clicking on the "Give Feedback" button located directly on completed or active event cards.',
            'what is walany'  => 'Walania is a dedicated event management platform designed to track campus events and registrations.'
        ];

        foreach ($faqs as $keyword => $answer) {
            if (strpos($msg, $keyword) !== false) {
                return $answer;
            }
        }
        return null;
    }

    public function getAvailableModels() {
        // FIXED: Query parameter construction patched
        $url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $this->apiKey;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        // Added local environment SSL bypass protections for XAMPP
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        
        if (isset($data['models'])) {
            foreach ($data['models'] as $model) {
                echo "Model String: " . str_replace("models/", "", $model['name']) . "<br>";
            }
        } else {
            echo "Failed to retrieve models. Response: <pre>" . htmlspecialchars($response) . "</pre>";
        }
    }

    private function fetchGeminiResponse($currentPrompt, $sessionId) {
        // FIXED: Swapped to an active model string and added the missing '?key=' operator
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key=" . $this->apiKey;
        
        $dbHistory = $this->chatModel->getChatHistory($sessionId);
        $contentsArray = [];

        // Build the correct payload mapping
        foreach ($dbHistory as $chatRow) {
            $role = ($chatRow['sender'] === 'user') ? 'user' : 'model';
            $contentsArray[] = [
                "role" => $role,
                "parts" => [["text" => $chatRow['message']]]
            ];
        }

        if (empty($contentsArray)) {
            $contentsArray[] = [
                "role" => "user",
                "parts" => [["text" => $currentPrompt]]
            ];
        }

        // Clean Architecture: Pass your identity constraints cleanly using the formal systemInstruction API object
        $payload = [
            "contents" => $contentsArray,
            "systemInstruction" => [
                "parts" => [
                    ["text" => "You are the automated assistant for Walany, a campus event system. Keep responses helpful, precise, and under 3 sentences."]
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }
        }

        return "API Error (Status: " . $httpCode . "): " . ($response ? htmlspecialchars($response) : 'No response data payload returned');
    }
}