<?php

namespace App\Repositories;

use App\DTO\Requests\ProductListDTO;
use App\Models\Product;

class ProductRepository
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    /**
     * @throws \Exception
     */
    public function getList(ProductListDTO $requestData)
    {
        $additionalColumns = $this->productModel->collectAdditionalCityColumns();

        $limit = $requestData->getLimitOffsetDTO()->getLimit();
        $offset = $requestData->getLimitOffsetDTO()->getOffset() ?? 0;
        $queryWhere = null;
        if($requestData->getSearch()) {
            $queryWhere = "
                WHERE `name` LIKE '%".$requestData->getSearch()."%'
                OR `usage` LIKE '%".$requestData->getSearch()."%'
                OR `code` = '".$requestData->getSearch()."'
            ";
        }
        $query = "
            SELECT
                `name`,
                `code`,
                `weight`,
                `usage`
                $additionalColumns
            FROM
                products
            $queryWhere
            ORDER BY id DESC
            LIMIT $limit
            OFFSET $offset
        ";
        return $this->productModel->executeQuery($query)->fetchAll(\PDO::FETCH_ASSOC);
    }


}