<?php

namespace App\DTO;

use App\Entity\QueueBuilding;
use DateTimeImmutable;

readonly class QueueBuildingDTO
{
    private int $id;
    private string $name;
    private int $level;
    private DateTimeImmutable $startedAt;
    private DateTimeImmutable $finishedAt;

    public function __construct(QueueBuilding $queueBuilding)
    {
        $this->id = $queueBuilding->getId();
        $this->name = $queueBuilding->getPlanetBuilding()?->getBuilding()?->getName();
        $this->level = $queueBuilding->getPlanetBuilding()?->getLevel();
        $this->startedAt = $queueBuilding->getStartedAt();
        $this->finishedAt = $queueBuilding->getFinishedAt();
    }

    public function getId(): int
    {
        return $this->id;
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