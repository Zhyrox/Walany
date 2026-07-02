<?php
require_once __DIR__ . '/../../../core/Database.php';

class Registrant {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Generates a clean ABCD-1234 formatted reference token split evenly between letters and numbers
     */
    private function generateReferenceToken() {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        
        $part1 = '';
        $part2 = '';
        
        for ($i = 0; $i < 4; $i++) {
            $part1 .= $letters[rand(0, strlen($letters) - 1)];
        }
        
        for ($i = 0; $i < 4; $i++) {
            $part2 .= $numbers[rand(0, strlen($numbers) - 1)];
        }
        
        return $part1 . '-' . $part2;
    }

    public function save($data) {
        try {
            // 1. Generate a clean token and loop-check to ensure it's uniquely clear
            $isUnique = false;
            $referenceId = '';
            
            while (!$isUnique) {
                $referenceId = $this->generateReferenceToken();
                
                $checkSql = "SELECT COUNT(*) FROM walania_registrant WHERE reference_id = :ref";
                $checkStmt = $this->db->prepare($checkSql);
                $checkStmt->execute([':ref' => $referenceId]);
                
                if ($checkStmt->fetchColumn() == 0) {
                    $isUnique = true;
                }
            }

            // 2. Insert execution mapped cleanly to include event_id and reference_id
            $sql = "INSERT INTO walania_registrant (event_id, reference_id, first_name, middle_name, last_name, age, email, contact_number)
                    VALUES (:event_id, :reference_id, :first_name, :middle_name, :last_name, 0, :email, :contact_number)";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                ':event_id'       => $data['event_id'],
                ':reference_id'   => $referenceId,
                ':first_name'     => $data['first_name'],
                ':middle_name'    => !empty($data['middle_name']) ? $data['middle_name'] : null,
                ':last_name'      => $data['last_name'],
                ':email'          => $data['email'],
                ':contact_number' => $data['contact_number']
            ]);

            // Return the generated key string back to the controller layer
            return $referenceId;
            
        } catch (PDOException $e) {
            return false;
        }
    }
}