<?php
class UserModel{


    private $db;

    public function __construct($dbConnection){
        $this->db = $dbConnection;
    }

    public function getUserById($id){
        $stmt = $this->db->prepare("SELECT * FROM walania_user WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function loginUser($username, $password){
        $stmt = $this->db->prepare("SELECT * FROM walania_user WHERE username = ?");
        
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
    
    public function registerUser($username, $password){
        
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO walania_user (username, password) VALUES (?, ?)");

        return $stmt->execute([$username, $passwordHash]);
    }
}
?>