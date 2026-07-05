<?php
require_once __DIR__ . '/../../../core/Database.php';

class ManagerController {

    public function createManager() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        // FIX: Match the updated single-word role
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            return ['status' => 'error', 'message' => 'Unauthorized administrative access checkpoint.'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName  = trim($_POST['last_name'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $password  = $_POST['password'] ?? '';
            
            // Capture the target role from your form dropdown (default to 'planner' if not specified)
            $role      = trim($_POST['role'] ?? 'planner'); 

            // Guard: Enforce strict existence validation
            if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
                return ['status' => 'error', 'message' => 'Account creation failed: All form fields are required.'];
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['status' => 'error', 'message' => 'Invalid email address structure format.'];
            }

            // Secure Hash Generation using native system environment algorithms
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            try {
                $db = (new Database())->getConnection();
                
                // Check if email already exists
                $check = $db->prepare("SELECT id FROM `walania_managers` WHERE `email` = :email LIMIT 1");
                $check->execute(['email' => $email]);
                if ($check->fetch()) {
                    return ['status' => 'error', 'message' => 'Conflict: An administrator with this email already exists.'];
                }

                // UPDATED: Added the `role` column mapping to the insert statement
                $stmt = $db->prepare("INSERT INTO `walania_managers` (`first_name`, `last_name`, `email`, `password_hash`, `role`) VALUES (:first_name, :last_name, :email, :password_hash, :role)");
                $stmt->execute([
                    'first_name'    => $firstName,
                    'last_name'     => $lastName,
                    'email'         => $email,
                    'password_hash' => $passwordHash,
                    'role'          => $role
                ]);

                return ['status' => 'success', 'message' => 'New account provisioned successfully!'];
            } catch (PDOException $e) {
                return ['status' => 'error', 'message' => 'Database transaction breakdown: ' . $e->getMessage()];
            }
        }
        return ['status' => 'error', 'message' => 'Invalid operational intent request method.'];
    }

    public function updateManager($managerId) {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        // FIX: Changed 'System Manager' to 'admin'
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            return ['status' => 'error', 'message' => 'Unauthorized administrative access checkpoint.'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName  = trim($_POST['last_name'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $password  = $_POST['password'] ?? ''; 
            $role      = trim($_POST['role'] ?? 'planner'); // Capture updated role selection

            if (empty($firstName) || empty($lastName) || empty($email)) {
                return ['status' => 'error', 'message' => 'Profile updates require valid name and email fields.'];
            }

            try {
                $db = (new Database())->getConnection();
                
                if (!empty($password)) {
                    // Update profile details, role, AND overwrite password securely
                    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $db->prepare("UPDATE `walania_managers` SET `first_name` = :first_name, `last_name` = :last_name, `email` = :email, `password_hash` = :password_hash, `role` = :role WHERE `id` = :id");
                    $stmt->execute([
                        'first_name'    => $firstName,
                        'last_name'     => $lastName,
                        'email'         => $email,
                        'password_hash' => $passwordHash,
                        'role'          => $role,
                        'id'            => intval($managerId)
                    ]);
                } else {
                    // Update credentials and role without altering the active password hash
                    $stmt = $db->prepare("UPDATE `walania_managers` SET `first_name` = :first_name, `last_name` = :last_name, `email` = :email, `role` = :role WHERE `id` = :id");
                    $stmt->execute([
                        'first_name' => $firstName,
                        'last_name'  => $lastName,
                        'email'      => $email,
                        'role'       => $role,
                        'id'         => intval($managerId)
                    ]);
                }

                return ['status' => 'success', 'message' => 'Manager profile updated successfully.'];
            } catch (PDOException $e) {
                return ['status' => 'error', 'message' => 'Database update transaction breakdown: ' . $e->getMessage()];
            }
        }
        return ['status' => 'error', 'message' => 'Invalid operational intent request method.'];
    }
}