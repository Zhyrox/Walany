<?php
//author @elmer - Lahat ng babaguhin pasabi sakin

//UserModel.php
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

//RegistrantModel.php
class RegistrantModel{

}

//EventModel.php

//Db.php
class Database{
    private $dbh;

    public function __construct(){
        $dsn = 'mysql:host=127.0.0.1;dbname=walania;port=3307;charset=utf8mb4'; // <-- 3307 yung port nung sa pc ko palitan nyo nlng if ever
        $user = 'root';
        $pass = '';
        $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->dbh = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \RuntimeException('Database connection error: ' . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function getConnection(){
        return $this->dbh;
    }
}