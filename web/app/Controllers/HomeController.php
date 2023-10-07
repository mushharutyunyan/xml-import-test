<?php

namespace App\Controllers;

use App\DTO\Requests\LimitOffsetDTO;
use App\DTO\Requests\ProductListDTO;
use App\Repositories\ProductRepository;
use App\Requests\ProductListRequest;
use App\Responses\Response;

class HomeController
{
    /**
     * @throws \Exception
     */
    public function products(ProductListRequest $request): void
    {
        $requestData = $request->all();
        $results = (new ProductRepository())->getList(new ProductListDTO(
            new LimitOffsetDTO(
                $requestData['limit'],
                $requestData['offset'] ?? null,
            ),
            $requestData['search'] ?? null
        ));
        Response::jsonOk($results);
    }
}