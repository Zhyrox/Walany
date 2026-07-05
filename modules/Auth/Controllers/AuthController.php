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
                
                // Store standard session info
                $_SESSION['manager_id']   = $manager['id'];
                $_SESSION['manager_name'] = $manager['first_name'] . ' ' . $manager['last_name'];
                
                // Capture the actual database role string ('admin', 'planner', or 'registrar')
                $_SESSION['role']         = $manager['role'];

                return [
                    'status' => 'success',
                    'message' => 'Access granted.',
                    'role'    => $manager['role'] // Pass it back to the router context
                ];
            }

            // --- TEMPORARY DEBUG CHECK ---
            echo "<h1>Auth Debugger</h1>";
            if (!$manager) {
                echo "❌ Error: No user row was found in the database matching the email: <strong>" . htmlspecialchars($email) . "</strong><br>";
            } else {
                echo "✅ Success: Found a matching database row for this email!<br>";
                echo "ℹ️ Database Role String: <strong>" . htmlspecialchars($manager['role']) . "</strong><br>";
                echo "ℹ️ Password Typed Length: <strong>" . strlen($password) . " characters</strong><br>";
                echo "ℹ️ Database Password Hash String: <code style='background:#eee;padding:2px;'>" . htmlspecialchars($manager['password_hash']) . "</code><br>";
                
                // Let's do a hardcoded check right here to see if it verifies
                $test_verify = password_verify($password, $manager['password_hash']) ? 'TRUE' : 'FALSE';
                echo "❌ Verification Result: password_verify() returned <strong>" . $test_verify . "</strong><br>";
            }
            exit; // Freeze execution so we can inspect the data
            // ------------------------------

            return ['status' => 'error', 'message' => 'Access Denied. Invalid administrative credentials.'];

        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Authentication core fault: ' . $e->getMessage()];
            echo "ERROR BABABA";
        }
    }
}