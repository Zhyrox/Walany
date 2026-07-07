<?php
Class AuthModel{
    private $db;

    public function __construct($dbConnection){
        $this->db = $dbConnection;
    }

    public function loginAccount($email, $password){
        $stmt = $this->db->prepare("SELECT * FROM `walania_managers` WHERE `email` = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $manager = $stmt->fetch(PDO::FETCH_ASSOC);

        if($manager && password_verify($password, $manager['password_hash'])){
            return $manager;
        }
    }
}
?>