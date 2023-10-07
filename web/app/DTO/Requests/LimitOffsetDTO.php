<?php

namespace App\DTO\Requests;

class LimitOffsetDTO
{
    private int $limit;
    private ?int $offset;

    public function __construct(
        int $limit,
        ?int $offset
    )
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }


}