<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

abstract class BaseModel
{
    protected PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->getConnection();
    }
}
