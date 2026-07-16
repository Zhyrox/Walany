<?php
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../Models/ManagerModel.php';

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
            header("Location: /Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
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
            header("Location: /Walany/index.php?module=Admin&action=view_managers&status=error&message=" . urlencode('Unauthorized administrative access checkpoint.'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName  = trim($_POST['last_name'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $role      = trim($_POST['role'] ?? 'planner'); 

            if (empty($firstName) || empty($lastName) || empty($email)) {
                header("Location: /Walany/index.php?module=Admin&action=view_managers&status=error&message=" . urlencode('Account creation failed: All fields are required.'));
                exit;
            }

            try {
                $db = (new Database())->getConnection();
                
                // Check if email already exists
                $check = $db->prepare("SELECT id FROM `walania_managers` WHERE `email` = :email LIMIT 1");
                $check->execute(['email' => $email]);
                if ($check->fetch()) {
                    header("Location: /Walany/index.php?module=Admin&action=view_managers&status=error&message=" . urlencode('Conflict: A user with this email already exists.'));
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

                header("Location: /Walany/index.php?module=Admin&action=view_managers&status=success&message=" . urlencode('New account provisioned successfully!'));
                exit;

            } catch (PDOException $e) {
                // 1. Log the absolute descriptive raw traceback details to XAMPP error logs for the server administrator
                error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());

                // 2. Safely redirect the user to the generic error container view without leaking structure schemas
                header("Location: /Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
                exit;
            }
        }
        
        header("Location: /Walany/index.php?module=Admin&action=view_managers&status=error&message=" . urlencode('Invalid request method.'));
        exit;
    }

    public function updateManager() {
        if (session_status() === PHP_SESSION_NONE) { 
            session_start(); 
        }

        // 1. Ensure the user is logged in
        if (!isset($_SESSION['manager_id'])) {
            return ['status' => 'error', 'message' => 'Unauthorized access. Please log in.'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $managerId = intval($_SESSION['manager_id']);
            
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName  = trim($_POST['last_name'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $password  = $_POST['password'] ?? ''; 

            // FIX: If 'role' isn't submitted in the form (like for Registrars), 
            // fall back to their current session role so the database query doesn't fail.
            $role = trim($_POST['role'] ?? $_SESSION['role'] ?? '');

            if (empty($firstName) || empty($lastName) || empty($email) || empty($role)) {
                return ['status' => 'error', 'message' => 'All profile fields are required.'];
            }

            try {
                // Initialize your model
                $managerModel = new ManagerModel();

                // Check for email conflicts
                if ($managerModel->emailExists($email, $managerId)) {
                    return ['status' => 'error', 'message' => 'Conflict: This email is already registered to another account.'];
                }

                // Process password hashing if they want to change it
                $passwordHash = null;
                if (!empty($password)) {
                    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                }

                // Execute update via your Model
                $success = $managerModel->update($managerId, $firstName, $lastName, $email, $role, $passwordHash);

                if ($success) {
                    // Update the active session name in case they renamed themselves
                    $_SESSION['manager_name'] = $firstName . ' ' . $lastName;
                    
                    // Log the activity
                    $managerModel->logSystemActivity($managerId, "User updated their own profile credentials.");
                    
                    return ['status' => 'success', 'message' => 'Your profile was updated successfully.'];
                } else {
                    return ['status' => 'error', 'message' => 'Failed to write updates to the system.'];
                }

            } catch (PDOException $e) {
                // Log and gracefully redirect using our universal error fallback
                error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
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
            header("Location: /Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
            exit;
        }
    }
}