<?php
require_once __DIR__ . '/../../../core/Database.php';

class ManagerController {

    private function verifyAdminAccess() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
    }

    private function logEvent($db, $actionDetails) {
        try {
            $actorId = $_SESSION['manager_id'] ?? 0;
            $actorName = ($_SESSION['first_name'] ?? 'System') . ' ' . ($_SESSION['last_name'] ?? 'Admin');
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

            $logStmt = $db->prepare("INSERT INTO `walania_system_access_logs` (`actor_id`, `actor_name`, `action_details`, `ip_address`, `logged_at`) VALUES (:actor_id, :actor_name, :action_details, :ip_address, NOW())");
            $logStmt->execute([
                'actor_id'       => $actorId,
                'actor_name'     => $actorName,
                'action_details' => $actionDetails,
                'ip_address'     => $ipAddress
            ]);
        } catch (PDOException $e) {
            // 1. Log the absolute descriptive raw traceback details to XAMPP error logs for the server administrator
            error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());

            // 2. Safely redirect the user to the generic error container view without leaking structure schemas
            header("Location: /PHP_Project/Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
            exit;
        }
    }

    private function generateTemporaryPassword($length = 10) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = 'Wln-'; 
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }
    
    public function createManager() {
        if (!$this->verifyAdminAccess()) {
            header("Location: /PHP_Project/Walany/index.php?module=Admin&action=view_managers&status=error&message=" . urlencode('Unauthorized administrative access checkpoint.'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName  = trim($_POST['last_name'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $role      = trim($_POST['role'] ?? 'planner'); 

            if (empty($firstName) || empty($lastName) || empty($email)) {
                header("Location: /PHP_Project/Walany/index.php?module=Admin&action=view_managers&status=error&message=" . urlencode('Account creation failed: All fields are required.'));
                exit;
            }

            try {
                $db = (new Database())->getConnection();
                
                // Check if email already exists
                $check = $db->prepare("SELECT id FROM `walania_managers` WHERE `email` = :email LIMIT 1");
                $check->execute(['email' => $email]);
                if ($check->fetch()) {
                    header("Location: /PHP_Project/Walany/index.php?module=Admin&action=view_managers&status=error&message=" . urlencode('Conflict: A user with this email already exists.'));
                    exit;
                }

                // Generate plaintext temporary password
                $plainTextPassword = $this->generateTemporaryPassword();

                // Hash the password for safety verification checks
                $passwordHash = password_hash($plainTextPassword, PASSWORD_BCRYPT);

                // Insert into the database including the visible temp_password column
                $stmt = $db->prepare("INSERT INTO `walania_managers` (`first_name`, `last_name`, `email`, `password_hash`, `role`, `temp_password`) VALUES (:first_name, :last_name, :email, :password_hash, :role, :temp_password)");
                $stmt->execute([
                    'first_name'    => $firstName,
                    'last_name'     => $lastName,
                    'email'         => $email,
                    'password_hash' => $passwordHash,
                    'role'          => $role,
                    'temp_password' => $plainTextPassword 
                ]);

                // Write to the updated audit log table
                $this->logEvent($db, "Provisioned new system user: $email with environment role matrix: [$role]");

                header("Location: /PHP_Project/Walany/index.php?module=Admin&action=view_managers&status=success&message=" . urlencode('New account provisioned successfully!'));
                exit;

            } catch (PDOException $e) {
                // 1. Log the absolute descriptive raw traceback details to XAMPP error logs for the server administrator
                error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());

                // 2. Safely redirect the user to the generic error container view without leaking structure schemas
                header("Location: /PHP_Project/Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
                exit;
            }
        }
        
        header("Location: /PHP_Project/Walany/index.php?module=Admin&action=view_managers&status=error&message=" . urlencode('Invalid request method.'));
        exit;
    }

    public function updateManager() {
        if (!$this->verifyAdminAccess()) {
            return ['status' => 'error', 'message' => 'Unauthorized access checkpoint.'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // FIX: Trust the secure session variable over what came from the raw form post
            $managerId = intval($_SESSION['manager_id'] ?? 0);

            if ($managerId === 0) {
                return ['status' => 'error', 'message' => 'Access Denied: Invalid session context parameter.'];
            }

            $firstName = trim($_POST['first_name'] ?? '');
            $lastName  = trim($_POST['last_name'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $password  = $_POST['password'] ?? ''; 

            if (empty($firstName) || empty($lastName) || empty($email)) {
                return ['status' => 'error', 'message' => 'All structural profile fields are required.'];
            }

            try {
                $db = (new Database())->getConnection();

                if (!empty($password)) {
                    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $db->prepare("UPDATE `walania_managers` SET `first_name` = :first_name, `last_name` = :last_name, `email` = :email, `password_hash` = :password_hash, `temp_password` = NULL WHERE `id` = :id");
                    $stmt->execute([
                        'first_name' => $firstName, 
                        'last_name' => $lastName, 
                        'email' => $email,
                        'password_hash' => $passwordHash, 
                        'id' => $managerId
                    ]);
                } else {
                    $stmt = $db->prepare("UPDATE `walania_managers` SET `first_name` = :first_name, `last_name` = :last_name, `email` = :email WHERE `id` = :id");
                    $stmt->execute([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'id' => $managerId
                    ]);
                }

                // Keep session variables in sync with updates
                $_SESSION['manager_name'] = $firstName . ' ' . $lastName;

                $this->logEvent($db, "Admin updated their own credentials matrix parameters (ID: $managerId)");
                return ['status' => 'success', 'message' => 'Your administrator profile was updated successfully.'];
            } catch (PDOException $e) {
                // 1. Log the absolute descriptive raw traceback details to XAMPP error logs for the server administrator
                error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());

                // 2. Safely redirect the user to the generic error container view without leaking structure schemas
                header("Location: /PHP_Project/Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
                exit;
            }
        }
        return ['status' => 'error', 'message' => 'Invalid request method.'];
    }

    /**
     * Locked Key Regeneration: Only executes if the target user has a pending forgot request flag set to 1.
     */
    public function regenerateTempPassword($managerId) {
        if (!$this->verifyAdminAccess()) {
            return ['status' => 'error', 'message' => 'Unauthorized access checkpoint.'];
        }

        try {
            $db = (new Database())->getConnection();
            
            // Verify there is an active forgot request before proceeding
            $check = $db->prepare("SELECT `forgot_request`, `email` FROM `walania_managers` WHERE `id` = :id LIMIT 1");
            $check->execute(['id' => intval($managerId)]);
            $user = $check->fetch(PDO::FETCH_ASSOC);

            if (!$user || (int)$user['forgot_request'] !== 1) {
                return ['status' => 'error', 'message' => 'Action Blocked: A temporary key cannot be forced unless a Forgot Password Request is active.'];
            }

            $plainTextKey = $this->generateTemporaryPassword();
            $passwordHash = password_hash($plainTextKey, PASSWORD_BCRYPT);

            // Update password, push plain text to screen column, reset forgot flag request to 0
            $stmt = $db->prepare("UPDATE `walania_managers` SET `password_hash` = :password_hash, `temp_password` = :temp, `forgot_request` = 0 WHERE `id` = :id");
            $stmt->execute(['password_hash' => $passwordHash, 'temp' => $plainTextKey, 'id' => intval($managerId)]);

            $this->logEvent($db, "Approved forgot password request and issued temporary key for profile: " . $user['email']);
            return ['status' => 'success', 'message' => 'New temporary key issued and forgot password request cleared successfully.'];
        } catch (PDOException $e) {
            // 1. Log the absolute descriptive raw traceback details to XAMPP error logs for the server administrator
            error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());

            // 2. Safely redirect the user to the generic error container view without leaking structure schemas
            header("Location: /PHP_Project/Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
            exit;
        }
    }
}