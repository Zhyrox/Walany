<?php
require_once __DIR__ . '/../../../core/Database.php';

class AuthController {
    
    public function handleLogin() {
        // Safe to extract since router explicitly confirms POST status
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            return ['status' => 'error', 'message' => 'Please fill in all fields.'];
        }

        try {
            $dbInstance = new Database();
            $db = $dbInstance->getConnection();

            $stmt = $db->prepare("SELECT * FROM `walania_managers` WHERE `email` = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $manager = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($manager && password_verify($password, $manager['password_hash'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                $_SESSION['manager_id']   = $manager['id'];
                $_SESSION['manager_name'] = $manager['first_name'] . ' ' . $manager['last_name'];
                $_SESSION['role']         = 'System Manager';

                return [
                    'status' => 'success',
                    'message' => 'Access granted.'
                ];
            }

            return ['status' => 'error', 'message' => 'Access Denied. Invalid administrative credentials.'];

        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Authentication core fault: ' . $e->getMessage()];
        }
    }
}