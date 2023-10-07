<?php

namespace App\Services\Importer;

use App\Models\Product;

abstract class BaseImport
{
    protected array $fileNames;
    protected array $fileStructure;
    protected string $duplicateKeyUpdateString;

    private \XMLReader $reader;
    private string $insertValuesQueryString;
    private int $rowsQtyForInsert = 1000;
    private Product $productModel;
    private ?string $columnsQueryString = null;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->reader = new \XMLReader();
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        $mainTag = $this->fileStructure["main"];
        if (!$this->fileStructure['have_dynamic_city_columns']) {
            $this->collectColumnNamesForQueryString($this->fileStructure['columns']);
        }
        $i = 1;
        $incrementCityLoop = 1;
        foreach ($this->fileNames as $city => $files) {
            $duplicateKeyUpdateString = null;
            if ($this->fileStructure['have_dynamic_city_columns']) {
                $this->collectColumnNamesForQueryString($this->fileStructure['columns'], $city);
                $duplicateKeyUpdateString = str_replace('{city}', $city, $this->duplicateKeyUpdateString);
            }
            if (!$this->columnsQueryString) {
                throw new \Exception('Something went wrong...');
            }
            foreach ($files as $file) {
                $this->reader->open($file);
                while ($this->reader->read()) {
                    if ($this->reader->nodeType == \XMLReader::ELEMENT && $this->reader->name == $mainTag) {
                        $element = json_decode(json_encode(new \SimpleXMLElement($this->reader->readOuterXML())), true);
                        $currentValuesCombination = $this->collectValuesForQueryString($element);
                        if ($i === $this->rowsQtyForInsert) {
                            $this->insertValuesQueryString .= $currentValuesCombination . ")";
                            $this->executeImport($duplicateKeyUpdateString);
                            $this->insertValuesQueryString = '';
                            $i = 0;
                        } else {
                            $this->insertValuesQueryString .= $currentValuesCombination . "),";
                        }
                        $i++;
                    }
                }
            }
            if ($this->fileStructure['have_dynamic_city_columns'] || $incrementCityLoop === count($this->fileNames)) {
                if(strlen($this->insertValuesQueryString)) {
                    $this->insertValuesQueryString = substr($this->insertValuesQueryString, 0, -1); // remove ',' - created in line 57
                    $this->executeImport($duplicateKeyUpdateString);
                    $this->insertValuesQueryString = '';
                    $i = 0;
                }
            }
            $incrementCityLoop++;
        }
    }

    /**
     * @throws \Exception
     */
    private function executeImport(string $duplicateKeyUpdateString = null): void
    {
        try {
            $this->productModel->executeQuery("INSERT INTO products (" . $this->columnsQueryString . ") VALUES " . $this->insertValuesQueryString . " ON DUPLICATE KEY UPDATE " . ($duplicateKeyUpdateString ?? $this->duplicateKeyUpdateString));
        } catch (\Exception $exception) {
            throw new $exception;
        }
    }

    private function collectColumnNamesForQueryString(array $columns, string $concatValue = null): void
    {
        $this->columnsQueryString = '';
        $columnsQueryStringIteration = 0;
        foreach ($columns as $structure) {
            if ($columnsQueryStringIteration) {
                $this->columnsQueryString .= ',';
            }
            $this->columnsQueryString .= "`" . $structure['field'] . ($structure['concat'] ? $concatValue : null) . "`";
            $columnsQueryStringIteration++;
        }
    }

    private function collectValuesForQueryString(array $xmlElement): string
    {
        $currentValuesCombination = "(";
        $currentElementIteration = 0;
        foreach ($this->fileStructure["columns"] as $tagName => $columnStructure) {
            if ($currentElementIteration) {
                $currentValuesCombination .= ",";
            }
            if (isset($xmlElement[$tagName])) {
                if ($columnStructure['single']) {
                    $currentValuesCombination .= $xmlElement[$tagName] ? "'" . addslashes($xmlElement[$tagName]) . "'" : "null";
                } else {
                    $currentMultipleValueCombination = "";
                    if (!empty($xmlElement[$tagName])) {
                        $currentMultipleElementIteration = 0;
                        foreach ($xmlElement[$tagName] as $item) {
                            foreach ($item[0] ? $item : [$item] as $itemRow) {
                                if ($currentMultipleElementIteration) {
                                    $currentMultipleValueCombination .= $columnStructure['combine_symbol'] ?? '|';
                                }
                                $currentMultipleValue = "";
                                $currentMultipleValueIteration = 0;
                                foreach ($columnStructure['tags'] as $tag) {
                                    if ($itemRow[$tag]) {
                                        if ($currentMultipleValueIteration) {
                                            $currentMultipleValue .= "-";
                                        }
                                        $currentMultipleValue .= $itemRow[$tag];
                                        $currentMultipleValueIteration++;
                                    }
                                }
                                $currentMultipleValueCombination .= $currentMultipleValue;
                                $currentMultipleElementIteration++;
                                if ($columnStructure['take_first']) {
                                    break;
                                }
                            }
                        }
                    }
                    $currentValuesCombination .= $currentMultipleValueCombination ? "'" . addslashes($currentMultipleValueCombination) . "'" : "null";
                }
            } else {
                $currentValuesCombination .= "null";
            }
            $currentElementIteration++;
        }
        return $currentValuesCombination;
    }
}