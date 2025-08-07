<?php

namespace App\Entity;

use App\Repository\QueueBuildingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QueueBuildingRepository::class)]
class QueueBuilding
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $finishedAt = null;

    #[ORM\OneToOne(mappedBy: 'queue', cascade: ['persist'])]
    private ?PlanetBuildings $planetBuildings = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(\DateTimeImmutable $finishedAt): static
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    public function getPlanetBuildings(): ?PlanetBuildings
    {
        return $this->planetBuildings;
    }

    public function setPlanetBuildings(?PlanetBuildings $planetBuildings): static
    {
        // unset the owning side of the relation if necessary
        if ($planetBuildings === null && $this->planetBuildings !== null) {
            $this->planetBuildings->setQueue(null);
        }

        // set the owning side of the relation if necessary
        if ($planetBuildings !== null && $planetBuildings->getQueue() !== $this) {
            $planetBuildings->setQueue($this);
        }

        $this->planetBuildings = $planetBuildings;

        return $this;
    }
}
