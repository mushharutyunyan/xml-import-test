<?php

namespace App\DTO\Requests;

class ProductListDTO
{
    private LimitOffsetDTO $limitOffsetDTO;
    private ?string $search;

    public function __construct(
        LimitOffsetDTO $limitOffsetDTO,
        ?string $search
    )
    {
        $this->limitOffsetDTO = $limitOffsetDTO;
        $this->search = $search;
    }

    /**
     * @return LimitOffsetDTO
     */
    public function getLimitOffsetDTO(): LimitOffsetDTO
    {
        return $this->limitOffsetDTO;
    }

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

}