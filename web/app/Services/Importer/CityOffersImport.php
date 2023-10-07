<?php

namespace App\Services\Importer;

use App\Models\Product;

class CityOffersImport extends BaseImport
{
    protected array $fileNames;
    protected array $fileStructure = [
        "main" => "Предложение",
        "have_dynamic_city_columns" => true,
        "columns" => [
            "Код" => [
                "field" => "code",
                "single" => true
            ],
            "Наименование" => [
                "field" => "name",
                "single" => true
            ],
            "Цены" => [
                "field" => "price_",
                "tags" => [
                    "ЦенаЗаЕдиницу"
                ],
                "concat" => true,
                "single" => false,
                "take_first" => true
            ],
            "Количество" => [
                "field" => "quantity_",
                "concat" => true,
                "single" => true
            ],
        ],
    ];
    protected string $duplicateKeyUpdateString = '`price_{city}` = VALUES(price_{city}), `quantity_{city}` = VALUES(quantity_{city})';

    public function __construct(
        array $fileNames
    )
    {
        parent::__construct();
        $this->fileNames = $fileNames;
    }
}