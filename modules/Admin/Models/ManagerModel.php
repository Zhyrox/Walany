<?php
require_once __DIR__ . '/../../../core/Database.php';

class ManagerModel {
    private $db;

    public function __construct() {
        try {
            $this->db = (new Database())->getConnection();
        } catch (PDOException $e) {
            error_log("MODEL ENGINE FAILURE (Constructor): " . $e->getMessage());
            throw $e;
        }
    }

    public function getAllManagers() {
        try {
            $stmt = $this->db->query("SELECT id, first_name, last_name, email, role, created_at, temp_password, forgot_request FROM `walania_managers` ORDER BY id DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("MODEL ENGINE FAILURE (getAllManagers): " . $e->getMessage());
            throw $e;
        }
    }

    public function getManagerById($id) {
        try {
            $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, role, temp_password, forgot_request FROM `walania_managers` WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => intval($id)]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("MODEL ENGINE FAILURE (getManagerById): " . $e->getMessage());
            throw $e;
        }
    }

    public function emailExists($email, $excludeId = null) {
        try {
            if ($excludeId) {
                $stmt = $this->db->prepare("SELECT id FROM `walania_managers` WHERE `email` = :email AND id != :id LIMIT 1");
                $stmt->execute(['email' => $email, 'id' => intval($excludeId)]);
            } else {
                $stmt = $this->db->prepare("SELECT id FROM `walania_managers` WHERE `email` = :email LIMIT 1");
                $stmt->execute(['email' => $email]);
            }
            return $stmt->fetch() ? true : false;
        } catch (PDOException $e) {
            error_log("MODEL ENGINE FAILURE (emailExists): " . $e->getMessage());
            throw $e;
        }
    }

    public function create($firstName, $lastName, $email, $passwordHash, $role, $tempPassword = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO `walania_managers` (`first_name`, `last_name`, `email`, `password_hash`, `role`, `temp_password`) VALUES (:first_name, :last_name, :email, :password_hash, :role, :temp_password)");
            return $stmt->execute([
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'email'         => $email,
                'password_hash' => $passwordHash,
                'role'          => $role,
                'temp_password' => $tempPassword
            ]);
        } catch (PDOException $e) {
            error_log("MODEL ENGINE FAILURE (create): " . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $firstName, $lastName, $email, $role, $passwordHash = null) {
        try {
            if ($passwordHash) {
                $stmt = $this->db->prepare("UPDATE `walania_managers` SET `first_name` = :first_name, `last_name` = :last_name, `email` = :email, `password_hash` = :password_hash, `role` = :role, `temp_password` = NULL WHERE `id` = :id");
                return $stmt->execute([
                    'first_name'    => $firstName,
                    'last_name'     => $lastName,
                    'email'         => $email,
                    'password_hash' => $passwordHash,
                    'role'          => $role,
                    'id'            => intval($id)
                ]);
            } else {
                $stmt = $this->db->prepare("UPDATE `walania_managers` SET `first_name` = :first_name, `last_name` = :last_name, `email` = :email, `role` = :role WHERE `id` = :id");
                return $stmt->execute([
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $email,
                    'role'       => $role,
                    'id'         => intval($id)
                ]);
            }
        } catch (PDOException $e) {
            error_log("MODEL ENGINE FAILURE (update): " . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM `walania_managers` WHERE id = :id");
            return $stmt->execute(['id' => intval($id)]);
        } catch (PDOException $e) {
            error_log("MODEL ENGINE FAILURE (delete): " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Standardized System Audit Log Writer
     */
    public function logSystemActivity($actorId, $actionDetails) {
        try {
            // Write directly to your unified system log layout
            $stmt = $this->db->prepare("INSERT INTO `walania_system_access_logs` (`actor_id`, `action_details`, `logged_at`) VALUES (:actor_id, :action, NOW())");
            $stmt->execute([
                'actor_id' => intval($actorId), 
                'action'   => $actionDetails
            ]);
        } catch (PDOException $e) {
            error_log("MODEL ENGINE FAILURE (logSystemActivity): " . $e->getMessage());
            throw $e;
        }
    }
}