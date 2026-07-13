<?php
require_once __DIR__ . '/../../../core/Database.php';

class ManagerModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAllManagers() {
        $stmt = $this->db->query("SELECT id, first_name, last_name, email, role, created_at FROM `walania_managers` ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getManagerById($id) {
        $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, role FROM `walania_managers` WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => intval($id)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function emailExists($email, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT id FROM `walania_managers` WHERE `email` = :email AND id != :id LIMIT 1");
            $stmt->execute(['email' => $email, 'id' => intval($excludeId)]);
        } else {
            $stmt = $this->db->prepare("SELECT id FROM `walania_managers` WHERE `email` = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
        }
        return $stmt->fetch() ? true : false;
    }

    public function create($firstName, $lastName, $email, $passwordHash, $role) {
        $stmt = $this->db->prepare("INSERT INTO `walania_managers` (`first_name`, `last_name`, `email`, `password_hash`, `role`) VALUES (:first_name, :last_name, :email, :password_hash, :role)");
        return $stmt->execute([
            'first_name'    => $firstName,
            'last_name'     => $lastName,
            'email'         => $email,
            'password_hash' => $passwordHash,
            'role'          => $role
        ]);
    }

    public function update($id, $firstName, $lastName, $email, $role, $passwordHash = null) {
        if ($passwordHash) {
            $stmt = $this->db->prepare("UPDATE `walania_managers` SET `first_name` = :first_name, `last_name` = :last_name, `email` = :email, `password_hash` = :password_hash, `role` = :role WHERE `id` = :id");
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
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM `walania_managers` WHERE id = :id");
        return $stmt->execute(['id' => intval($id)]);
    }

    /**
     * System Admin Mapping requirement: Audit Log Writer
     */
    public function logSystemActivity($actorId, $actionDetails) {
        try {
            // Assumes a basic system logs table exists or writes gracefully without crashing
            $stmt = $this->db->prepare("INSERT INTO `system_access_logs` (`actor_id`, `action_details`, `logged_at`) VALUES (:actor_id, :action, NOW())");
            $stmt->execute(['actor_id' => $actorId, 'action' => $actionDetails]);
        } catch (PDOException $e) {
            // 1. Log it locally to identify which model query failed
            error_log("MODEL ENGINE FAILURE: " . $e->getMessage());
            
            // 2. Re-throw it so the Controller's catch block can catch it and redirect the user
            throw $e;
        }
    }
}