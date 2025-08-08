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
    private int $constructionTime;

    public function __construct(Building $building, int $level, array $pricesDto, int $constructionTime)
    {
        $this->id = $building->getId();
        $this->name = $building->getName();
        $this->image = $building->getImage();
        $this->description = $building->getDescription();
        $this->level = $level;
        $this->constructionTime = $constructionTime;
        $this->prices = $pricesDto;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getConstructionTime(): string
    {
        $seconds = $this->constructionTime;
        $d = floor($seconds / (3600 * 24));
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = floor($seconds % 60);

        $parts = [];
        if ($d > 0) {
            $parts[] = "{$d}j";
        }

        if ($h > 0 || $d > 0) {
            $parts[] = "{$h}h";
        }

        if ($m > 0 || $h > 0 || $d > 0) {
            $parts[] = "{$m}min";
        }
        $parts[] = "{$s}s";
        return implode(' ', $parts);
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