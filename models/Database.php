<?php

require_once __DIR__ . '/../Config.php';

class Database{
    private $dbh;

    public function __construct(){
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT . ';charset=' . DB_CHAR;
        $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->dbh = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (\PDOException $e) {
            // Securely records exact details (like port conflicts or bad passwords) in private server logs
            error_log('Database connection error: ' . $e->getMessage());
            // Masked message sent to front-end to protect system integrity
            throw new \RuntimeException('Database connection error. Please contact an administrator.', (int)$e->getCode());

        }
    }

    public function getConnection(){
        return $this->dbh;
    }
}
?>