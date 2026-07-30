<?php
class ChatSession {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getDbConnection() {
        return $this->db;
    }

    public function getOrCreateSession($token) {
        $stmt = $this->db->prepare("SELECT * FROM `walania_chat_sessions` WHERE `session_token` = :token LIMIT 1");
        $stmt->execute([':token' => $token]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$session) {
            $stmt = $this->db->prepare("INSERT INTO `walania_chat_sessions` (`session_token`, `status`) VALUES (:token, 'bot')");
            $stmt->execute([':token' => $token]);
            return ['id' => $this->db->lastInsertId(), 'session_token' => $token, 'status' => 'bot'];
        }
        return $session;
    }

    public function saveMessage($sessionId, $sender, $message) {
        $stmt = $this->db->prepare("INSERT INTO `walania_chat_messages` (`session_id`, `sender`, `message`, `created_at`) VALUES (:sid, :sender, :msg, NOW())");
        return $stmt->execute([
            ':sid' => $sessionId,
            ':sender' => $sender,
            ':msg' => $message
        ]);
    }

    public function getChatHistory($sessionId) {
        $stmt = $this->db->prepare("SELECT `sender`, `message`, `created_at` FROM `walania_chat_messages` WHERE `session_id` = :sid ORDER BY `id` ASC");
        $stmt->execute([':sid' => $sessionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function requestHumanTakeover($sessionId) {
        $stmt = $this->db->prepare("UPDATE `walania_chat_sessions` SET `status` = 'human' WHERE `id` = :sid");
        return $stmt->execute([':sid' => $sessionId]);
    }

    public function updateSessionStatus($sessionId, $status) {
        $stmt = $this->db->prepare("UPDATE `walania_chat_sessions` SET `status` = :status WHERE `id` = :sid");
        return $stmt->execute([':status' => $status, ':sid' => $sessionId]);
    }

    public function getEscalatedSessions() {
        $stmt = $this->db->prepare("SELECT * FROM `walania_chat_sessions` WHERE `status` = 'human' ORDER BY `id` DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getRecentSessions() {
        $stmt = $this->db->prepare("
            SELECT 
                s.`id`, 
                s.`session_token`, 
                s.`status`, 
                s.`created_at`,
                COALESCE(
                    (SELECT MAX(m.`created_at`) FROM `walania_chat_messages` m WHERE m.`session_id` = s.`id`), 
                    s.`created_at`
                ) AS `updated_at`
            FROM `walania_chat_sessions` s
            ORDER BY `updated_at` DESC, s.`id` DESC
            LIMIT 20
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Retrieve the primary session ID using the user's session token.
     * 
     * @param string $token
     * @return int|null
     */
    public function getSessionIdByToken($token) {
        $stmt = $this->db->prepare("SELECT `id` FROM `walania_chat_sessions` WHERE `session_token` = :token LIMIT 1");
        $stmt->execute([':token' => $token]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? (int)$result['id'] : null;
    }

    /**
     * Release a human takeover session back to AI Bot mode.
     * 
     * @param int $sessionId
     * @return bool
     */
    public function releaseSessionToBot($sessionId) {
        return $this->updateSessionStatus($sessionId, 'bot');
    }
}