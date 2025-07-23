<?php

namespace App\DTO;

readonly class PriceDTO
{
    public function __construct(public readonly string $resourceName, public readonly float $quantity) {}

    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }
}