<?php
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../Models/AuthModel.php';

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

            $authModel = new AuthModel($db);
            $manager = $authModel->loginAccount($email, $password);

            if ($manager) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                // Store standard session info
                $_SESSION['manager_id']   = $manager['id'];
                $_SESSION['manager_name'] = $manager['first_name'] . ' ' . $manager['last_name'];
                
                // Capture the actual database role string ('admin', 'planner', or 'registrar')
                $_SESSION['role']         = $manager['role'];

                return [
                    'status' => 'success',
                    'message' => 'Access granted.',
                    'role'    => $manager['role']
                ];
            } else {
                return ['status' => 'error', 'message' => 'Access Denied. Invalid administrative credentials.'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Authentication core fault: ' . $e->getMessage()];
        }
    }
}