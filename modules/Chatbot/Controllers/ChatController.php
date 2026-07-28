<?php
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../../../core/Config.php';
require_once __DIR__ . '/../Models/ChatSessions.php';

class ChatController {
    private $chatModel;
    private $apiKey = GEMINI_CHATBOT_KEY;

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
        $suggestions = $this->getSuggestionPrompts();
        
        require_once __DIR__ . '/../Views/chat-window.php';
    }

    public function getSuggestionPrompts() {
        return [
            ['label' => 'Available events', 'message' => 'Tell me about the available campus events'],
            ['label' => 'Register', 'message' => 'How do I register for an event?'],
            ['label' => 'Feedback help', 'message' => 'Give me help with feedback or complaints'],
            ['label' => 'Talk to agent', 'message' => 'Talk to a human agent']
        ];
    }

    public function sendMessage() {
        if (ob_get_length()) { ob_clean(); }
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) { 
            session_start(); 
        }
        
        // 1. Ensure chat_token exists (auto-generate if missing)
        if (empty($_SESSION['chat_token'])) {
            $_SESSION['chat_token'] = bin2hex(random_bytes(16));
        }
        $token = $_SESSION['chat_token'];

        // 2. Read message from raw JSON payload OR standard $_POST
        $input = json_decode(file_get_contents('php://input'), true);
        $userMsg = trim($input['message'] ?? $_POST['message'] ?? $_POST['prompt'] ?? '');

        // 3. Validation check
        if (empty($userMsg)) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Invalid data payload: Message string is empty.'
            ]);
            exit;
        }

        $session = $this->chatModel->getOrCreateSession($token);
        $this->chatModel->saveMessage($session['id'], 'user', $userMsg);

        // 1. Check for human agent escalation request or active takeover
        if ($session['status'] === 'human' || stripos($userMsg, 'talk to human') !== false || stripos($userMsg, 'agent') !== false) {
            if ($session['status'] === 'human') {
                echo json_encode([
                    'status' => 'success', 
                    'reply' => null, 
                    'mode' => 'human'
                ]);
                exit;
            }
            
            $this->chatModel->requestHumanTakeover($session['id']);
            $reply = "I am transferring your session to a live campus coordinator. They will review this conversation thread shortly.";
            $this->chatModel->saveMessage($session['id'], 'agent', $reply);
            
            echo json_encode(['status' => 'success', 'reply' => $reply, 'mode' => 'human']);
            exit;
        }

        // 2. Check local FAQs first
        $faqReply = $this->checkLocalFAQs($userMsg);
        if ($faqReply) {
            $this->chatModel->saveMessage($session['id'], 'bot', $faqReply);
            echo json_encode(['status' => 'success', 'reply' => $faqReply, 'mode' => 'bot']);
            exit;
        }

        // 3. Query Gemini Model
        $botReply = $this->fetchGeminiResponse($userMsg, $session['id']);

        $confusedPhrases = [
            "i do not know", "i'm not sure", "i cannot answer", 
            "sorry, as an ai", "i don't have information", "api error"
        ];
        
        $isConfused = false;
        foreach ($confusedPhrases as $phrase) {
            if (stripos($botReply, $phrase) !== false) {
                $isConfused = true;
                break;
            }
        }

        if ($isConfused) {
            $this->chatModel->requestHumanTakeover($session['id']);
            $escalationReply = "I am transferring your session to a live campus coordinator. They will review this conversation thread shortly.";
            $this->chatModel->saveMessage($session['id'], 'agent', $escalationReply);
            
            echo json_encode(['status' => 'success', 'reply' => $escalationReply, 'mode' => 'human']);
            exit;
        }

        $this->chatModel->saveMessage($session['id'], 'bot', $botReply);
        echo json_encode(['status' => 'success', 'reply' => $botReply, 'mode' => 'bot']);
        exit;
    }

    public function clearChat() {
        if (ob_get_length()) { ob_clean(); }
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        $_SESSION['chat_token'] = bin2hex(random_bytes(16));
        $this->chatModel->getOrCreateSession($_SESSION['chat_token']);

        echo json_encode(['status' => 'success', 'message' => 'Chat context successfully reset']);
        exit;
    }

    public function getHistoryApi() {
        if (ob_get_length()) { ob_clean(); }
        header('Content-Type: application/json');

        $sessionId = intval($_GET['session_id'] ?? 0);
        if (!$sessionId) {
            echo json_encode([]);
            exit;
        }

        $history = $this->chatModel->getChatHistory($sessionId);
        echo json_encode($history);
        exit;
    }

    public function adminReplyApi() {
        if (ob_get_length()) { ob_clean(); }
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $sessionId = intval($input['session_id'] ?? 0);
        $message = trim($input['message'] ?? '');

        if ($sessionId && !empty($message)) {
            $this->chatModel->saveMessage($sessionId, 'agent', $message);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Missing session or message content']);
        }
        exit;
    }

    public function resolveSessionApi() {
        if (ob_get_length()) { ob_clean(); }
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $sessionId = intval($input['session_id'] ?? 0);

        if ($sessionId) {
            $this->chatModel->updateSessionStatus($sessionId, 'bot');
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid session ID']);
        }
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

    private function getLiveEventsSummary() {
        try {
            // Query only public-facing, relevant columns from active events
            $stmt = $this->chatModel->getDbConnection()->prepare("
                SELECT 
                    `name`, 
                    `category`, 
                    `event_date`, 
                    `location`, 
                    `price`, 
                    `description`, 
                    `is_featured`, 
                    `open_registration` 
                FROM `walania_event` 
                WHERE `is_active` = 1 
                ORDER BY `event_date` ASC 
                LIMIT 10
            ");
            $stmt->execute();
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($events)) {
                return "There are currently no active events scheduled in the database.";
            }

            $summary = "Official Active Events List:\n";
            foreach ($events as $e) {
                $priceText = ($e['price'] > 0) ? "₱" . number_format($e['price'], 2) : "Free";
                $featuredTag = ($e['is_featured'] == 1) ? " [FEATURED EVENT]" : "";
                $regStatus = ($e['open_registration'] == 1) ? "Registration Open" : "Registration Closed";

                $summary .= sprintf(
                    "- Event: %s%s\n  Category: %s | Date: %s | Location: %s\n  Price: %s | Status: %s\n  Description: %s\n\n",
                    $e['name'],
                    $featuredTag,
                    $e['category'],
                    $e['event_date'],
                    $e['location'],
                    $priceText,
                    $regStatus,
                    $e['description']
                );
            }
            return $summary;
        } catch (Exception $ex) {
            return "Unable to fetch events list at the moment.";
        }
    }

    private function fetchGeminiResponse($currentPrompt, $sessionId) {
        // Standard Gemini Flash Endpoint
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key=" . $this->apiKey;

        // 1. Get live events from walania_event table
        $liveEventsContext = $this->getLiveEventsSummary();

        // 2. Build system instruction text
        $systemInstructionText = "You are the official student virtual assistant named 'Wally' for the Walania campus event platform.\n"
            . "TONE & BEHAVIOR:\n"
            . "- Be polite, welcoming, professional, and natural.\n"
            . "- NEVER mention technical terms like 'database', 'system records', 'backend', 'entries', or 'provided context'. Speak naturally as a staff member.\n"
            . "- If information or registration steps are not listed in the event details, direct the user politely to visit the event page or contact the event coordinator.\n"
            . "- Keep responses clear and concise (under 3 sentences).\n\n"
            . "CURRENT CAMPUS EVENTS:\n" 
            . $liveEventsContext;

        // 3. Simple, guaranteed valid payload format (single turn test)
        $payload = [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => (string)$currentPrompt]
                    ]
                ]
            ],
            "systemInstruction" => [
                "parts" => [
                    ["text" => $systemInstructionText]
                ]
            ]
        ];

        $jsonPayload = json_encode($payload);

        // 4. Send cURL Request
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // DEBUG: If it fails, return both the status code and the raw API response!
        if ($httpCode !== 200) {
            return "DEBUG ERROR (HTTP $httpCode): " . $response . " | SENT PAYLOAD: " . $jsonPayload;
        }

        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }
        }

        return "Unable to fetch response from AI model right now.";
    }
}