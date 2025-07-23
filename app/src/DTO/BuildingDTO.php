<?php

namespace App\DTO;

use App\Entity\Building;

readonly class BuildingDTO
{
    private int $id;
    private string $name;
    private string $image;
    /** @var PriceDTO[] $prices */
    private array $prices;
    private string $description;
    private int $level;
    private float $constructionTime;

    public function __construct(Building $building, int $level, array $pricesDto)
    {
        $this->id = $building->getId();
        $this->name = $building->getName();
        $this->image = '';
        $this->description = $building->getDescription();
        $this->level = $level;
        $this->constructionTime = 0;
        $this->prices = $pricesDto;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getConstructionTime(): float
    {
        return $this->constructionTime;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrices(): array
    {
        return $this->prices;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getName(): string
    {
        return $this->name;
    }

}