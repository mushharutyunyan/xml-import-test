<?php

namespace App\Models;

use System\DBConnection;

class Product extends BaseModel
{
    private string $tableName = 'products';
    public array $additionalFieldStructuresForCities = [
        'quantity' => 'MEDIUMINT',
        'price' => 'DECIMAL(8,2)'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function alterQuantityAndPriceFieldsByCities(array $cities): void
    {
        $columns = $this->getColumns();
        $columnsForAlter = $this->getColumnsForAlter($cities, $columns);
        if (!empty($columnsForAlter)) {
            $addColumnsQueryString = '';
            $i = 1;
            foreach ($columnsForAlter as $columnName => $columnType) {
                if($i !== count($columnsForAlter)) {
                    $addColumnsQueryString .= $columnName .' '.$columnType.', ';
                } else {
                    $addColumnsQueryString .= $columnName .' '.$columnType;
                }
                $i++;
            }
            $query = "
                ALTER TABLE " . $this->tableName . "
                ADD COLUMN (".$addColumnsQueryString.")
            ";
            $this->connection->pdo()->query($query);
        }
    }

    /**
     * @throws \Exception
     */
    public function getColumns(): array
    {
        $query = "SELECT column_name
            FROM INFORMATION_SCHEMA.COLUMNS
           WHERE table_name = '" . $this->tableName . "'
             AND table_schema = '" . $_ENV['MYSQL_DATABASE'] . "'";
        $queryResults = $this->connection->pdo()->query($query)->fetchAll();
        if (empty($queryResults)) {
            throw new \Exception($this->tableName . ' table not exist');
        }
        $columns = [];
        foreach ($queryResults as $row) {
            $columns[] = $row['column_name'];
        }
        return $columns;
    }

    private function getColumnsForAlter($cities, $columns): array
    {
        $columnsForAlter = [];
        foreach ($cities as $city) {
            foreach ($this->additionalFieldStructuresForCities as $name => $type) {
                if (!in_array($name . "_" . $city, $columns)) {
                    $columnsForAlter[$name . "_" . $city] = $type;
                }
            }
        }
        return $columnsForAlter;
    }

    /**
     * @throws \Exception
     */
    public function collectAdditionalCityColumns(): string
    {
        $tableColumns = $this->getColumns();
        $additionalColumnPrefixes = array_keys($this->additionalFieldStructuresForCities);
        $columns = null;
        foreach($additionalColumnPrefixes as $columnPrefix) {
            foreach($tableColumns as $column) {
                if (strpos($column, $columnPrefix) !== false) { // because PHP version is 7.4
                    $columns .= ",`".$column."`";
                }
            }
        }
        return $columns;
    }
}