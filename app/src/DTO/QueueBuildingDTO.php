<?php

namespace App\DTO;

use App\Entity\QueueBuilding;
use DateTimeImmutable;

readonly class QueueBuildingDTO
{
    private string $name;
    private int $level;
    private DateTimeImmutable $startedAt;
    private DateTimeImmutable $finishedAt;

    public function __construct(QueueBuilding $queueBuilding)
    {
        $this->name = $queueBuilding->getPlanetBuildings()?->getBuilding()?->getName();
        $this->level = $queueBuilding->getPlanetBuildings()?->getLevel();
        $this->startedAt = $queueBuilding->getStartedAt();
        $this->finishedAt = $queueBuilding->getFinishedAt();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getStartedAt(): DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getFinishedAt(): DateTimeImmutable
    {
        return $this->finishedAt;
    }
}