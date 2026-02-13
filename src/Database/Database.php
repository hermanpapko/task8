<?php

use PDO;
use PDOException;

class Database
{
    private ?PDO $connection = null;

    public function __construct(
        private string $host,
        private string $dbname,
        private string $user,
        private string $password
    ) {}

    public function getConnection(): ?PDO
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        $dsn = "pgsql:host={$this->host};dbname={$this->dbname}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->connection = new PDO($dsn, $this->user, $this->password, $options);
            return $this->connection;
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            return null;
        }
    }
}