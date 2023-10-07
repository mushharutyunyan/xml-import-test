<?php
namespace Migrations;

use System\DBConnection;

class CreateCheckAndFillOldValueFunction
{
    private DBConnection $connection;

    public function __construct()
    {
        $this->connection = new DBConnection();
    }

    public function run()
    {
        $query = "
            DROP FUNCTION IF EXISTS checkAndFillOldValue;
          
            CREATE FUNCTION checkAndFillOldValue ( old_value MEDIUMTEXT, new_value MEDIUMTEXT )
            RETURNS MEDIUMTEXT
            BEGIN
              DECLARE inc INT;
              DECLARE merged_value MEDIUMTEXT;
              DECLARE current_iteration_value TEXT;
              DECLARE until BOOLEAN;
              SET inc = 1; 
              SET until = true;
              SET merged_value = old_value;
              SET current_iteration_value = old_value;
                label: 
                 WHILE until DO
                    IF(LENGTH(SPLIT_STRING(new_value, '|', inc)) = 0) THEN
                        SET current_iteration_value = null;
                        SET until = false;
                    ELSE
                        SET current_iteration_value = SPLIT_STRING(new_value, '|', inc);
                    END IF;
                    IF(current_iteration_value IS NOT NULL AND merged_value LIKE CONCAT('%',current_iteration_value,'%')) THEN
                        SET current_iteration_value = null;
                    END IF;
                    IF(current_iteration_value IS NOT NULL) THEN
                        SET merged_value = CONCAT(merged_value, '|', current_iteration_value);
                    END IF;
                    SET inc = inc + 1;
                 END WHILE label;
              RETURN merged_value;
            END; $$
        ";
        $this->connection->pdo()->query($query);
    }
}