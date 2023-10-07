<?php
namespace System;

class DBConnection
{
    /**
     * @return \PDO
     */
    public function pdo()
    {
        // For update - Do not forget to change main .env file too
        $host = $_ENV['MYSQL_HOST'];
        $dbname = $_ENV['MYSQL_DATABASE'];
        $user = $_ENV['MYSQL_USER'];
        $pass = $_ENV['MYSQL_PASSWORD'];
        try {
            $DBH = new \PDO("mysql:host=$host;dbname=$dbname", $user, $pass, array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4;"
            ));
            $DBH->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $DBH;
        } catch (\PDOException $e) {
            throw new $e;
        }
    }

}