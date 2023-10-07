<?php

namespace App\Services\Importer;

use App\Models\Product;
use Helpers\Str;

class Importer
{
    private string $dir;
    private array $fileStructure = [
        "import" => [
            "files" => []
        ],
        "offers" => [
            "files" => []
        ],
    ];

    private Product $productModel;
    private array $cityNames = [];

    public function __construct(string $dir)
    {
        $this->dir = $dir;
        $this->productModel = new Product();
    }

    /**
     * @throws \Exception
     */
    public function run(): void
    {
        $this->collectFiles();
        $this->productModel->alterQuantityAndPriceFieldsByCities($this->cityNames);
        (new ProductsImport($this->fileStructure['import']['files']))->run();
        (new CityOffersImport($this->fileStructure['offers']['files']))->run();
    }

    private function collectFiles(): void
    {
        foreach (glob($this->dir . '/*.xml*') as $file) {
            $baseName = basename($file, '.xml');
            foreach (array_keys($this->fileStructure) as $key) {
                preg_match("/(?<=$key)(.*)(?=_)/", $baseName, $matchedCity);
                if (!empty($matchedCity)) {
                    $cityName = $matchedCity[0];
                    if (!in_array(Str::slug($cityName), $this->cityNames)) {
                        $this->cityNames[] = Str::slug($cityName);
                    }
                    $this->fileStructure[$key]['files'][$cityName][] = $file;
                }
            }
        }
    }
}