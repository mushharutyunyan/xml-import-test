<?php
namespace Migrations;

use System\DBConnection;

class CreateSplitStringFunction
{
    private DBConnection $connection;
    public function __construct()
    {
        $this->connection = new DBConnection();
    }

    public function run()
    {
        $query = "
            DROP FUNCTION IF EXISTS SPLIT_STRING;
            CREATE FUNCTION SPLIT_STRING(
              str MEDIUMTEXT,
              delim VARCHAR(12),
              pos INT
            )
            RETURNS VARCHAR(255)
            RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(str, delim, pos),
                   CHAR_LENGTH(SUBSTRING_INDEX(str, delim, pos-1)) + 1),
                   delim, '');
        ";
        $this->connection->pdo()->query($query);
    }
}