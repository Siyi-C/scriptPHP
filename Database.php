<?php

class Database {
    private static ?Database $instance = null;
    private PDO $pdo;

     private function __construct()
    {
        $host = ($_ENV['DB_HOST']);
        $db   = ($_ENV['MYSQL_DATABASE']);
        $user = ($_ENV['MYSQL_USER']);
        $pass = ($_ENV['MYSQL_PASSWORD']);

        $dsn = "mysql:host=$host;port=3306;dbname=$db;charset=utf8mb4";

        try {
            $this->pdo =new PDO($dsn, $user, $pass);
            // set the PDO error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected successfully";
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit();
        }
    }

    // get the singleton instance
    public static function getInstance(): self
    {
        // check if the instance has not been created yet
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        if (!isset($this->pdo)) {
            throw new RuntimeException("PDO connection not initialized.");
        }
        return $this->pdo;
    }
}