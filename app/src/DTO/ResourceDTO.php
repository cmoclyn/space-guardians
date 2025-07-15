<?php

namespace App\DTO;

use App\Entity\PlanetResource;
use DateTime;
use DateTimeInterface;

readonly class ResourceDTO
{
    private string $name;
    private string $image;
    private float $quantity;
    private float $production;
    private DateTimeInterface $date;

    public function __construct(PlanetResource $planetResource)
    {
        $this->name = $planetResource->getResource()?->getName() ?? '';
        $this->image = $planetResource->getResource()?->getImage() ?? '';
        $this->quantity = $planetResource->getQuantity();
        $this->production = 100;
        $this->date = $planetResource->getDate() ?? new DateTime();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getProduction(): float
    {
        return $this->production;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}