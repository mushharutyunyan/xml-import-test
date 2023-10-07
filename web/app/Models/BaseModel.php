<?php

namespace App\Models;

use System\DBConnection;

class BaseModel
{
    protected DBConnection $connection;

    public function __construct()
    {
        $this->connection = new DBConnection();
    }

    public function executeQuery(string $query)
    {
        return $this->connection->pdo()->query($query);
    }
}