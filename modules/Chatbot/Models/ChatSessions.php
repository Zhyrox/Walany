<?php
class ChatSession {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
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
        $stmt = $this->db->prepare("INSERT INTO chat_messages (session_id, sender, message, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$sessionId, $sender, $message]);
    }

    public function getChatHistory($sessionId) {
        $stmt = $this->db->prepare("SELECT `sender`, `message`, `timestamp` FROM `walania_chat_messages` WHERE `session_id` = :sid ORDER BY `id` ASC");
        $stmt->execute([':sid' => $sessionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function requestHumanTakeover($sessionId) {
        $stmt = $this->db->prepare("UPDATE `walania_chat_sessions` SET `status` = 'human' WHERE `id` = :sid");
        return $stmt->execute([':sid' => $sessionId]);
    }

    public function updateSessionStatus($sessionId, $status) {
        $stmt = $this->db->prepare("UPDATE chat_sessions SET status = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$status, $sessionId]);
    }
}