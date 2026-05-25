<?php

require_once "db.php";

//new code @elmer

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
        $dsn = 'mysql:host=localhost;dbname=walania;charset=utf8mb4';
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

    // old codes
    // usermodel
    function registerUser($username, $password) {
        $pdo = walania_db();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO walania_user (username, password) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$username, $hashedPassword]);
    }

    function loginUser($username) {
        $pdo = walania_db();
        $sql = "SELECT * FROM walania_user WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    function getUserById($id) {
        $pdo = walania_db();
        $sql = "SELECT * FROM walania_user WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    //db

    function walania_db(): PDO
    {
        static $pdo = null;

        if ($pdo instanceof PDO) {
            return $pdo;
        }

        $dsn = 'mysql:host=localhost;dbname=walania;charset=utf8mb4';
        $user = 'root';
        $pass = '';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        } catch (\PDOException $e) {
            throw new \RuntimeException('Database connection error: ' . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }



?>