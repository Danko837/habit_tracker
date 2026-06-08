<?php

declare(strict_types=1);

class Database
{
    private string $host;
    private int $port;
    private string $dbName;
    private string $username;
    private string $password;
    private string $charset;
    private ?PDO $connection = null;

    public function __construct(array $config = [])
    {
        $this->host = $config['host'] ?? '127.0.0.1';
        $this->port = (int) ($config['port'] ?? 3306);
        $this->dbName = $config['dbname'] ?? 'habit_tracker';
        $this->username = $config['username'] ?? 'root';
        $this->password = $config['password'] ?? '';
        $this->charset = $config['charset'] ?? 'utf8mb4';
    }

    public function getConnection(): PDO
    {
        if ($this->connection instanceof PDO) {
            return $this->connection;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $this->host,
            $this->port,
            $this->dbName,
            $this->charset
        );

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException('Database connection failed.', 0, $exception);
        }

        return $this->connection;
    }
}
