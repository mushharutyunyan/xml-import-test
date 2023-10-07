<?php

namespace App\Services\Importer;

use App\Models\Product;

class ProductsImport extends BaseImport
{
    protected array $fileNames;
    protected array $fileStructure = [
        "main" => "Товар",
        "have_dynamic_city_columns" => false,
        "columns" => [
            "Код" => [
                "field" => "code",
                "single" => true
            ],
            "Наименование" => [
                "field" => "name",
                "single" => true
            ],
            "Вес" => [
                "field" => "weight",
                "single" => true
            ],
            "Взаимозаменяемости" => [
                "single" => false,
                "field" => "usage",
                "combine_symbol" => '|',
                "tags" => [
                    "Марка",
                    "Модель",
                    "КатегорияТС"
                ]
            ]
        ],
    ];

    protected string $duplicateKeyUpdateString = '`usage` = IF(`usage` IS NOT NULL, checkAndFillOldValue(`usage`,VALUES(`usage`)), VALUES(`usage`))';

    public function __construct(
        array $fileNames
    )
    {
        $this->fileNames = $fileNames;
        parent::__construct();
    }
}