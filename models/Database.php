<?php

/*

Author: Elmer
Notes: Palitan port based sa kung ano yung port na gamit nyo sa pc nyo.

*/

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
?>