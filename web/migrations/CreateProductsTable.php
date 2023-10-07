<?php
namespace Migrations;

use System\DBConnection;
class CreateProductsTable
{
    private DBConnection $connection;
    public function __construct()
    {
        $this->connection = new DBConnection();
    }

    public function run()
    {
        $query = "CREATE TABLE if not exists products (
            id int NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            code BIGINT NOT NULL,
            weight DECIMAL(6,2),
            `usage` MEDIUMTEXT,
            PRIMARY KEY (id),
            UNIQUE (code)
        ) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->connection->pdo()->query($query);
    }
}