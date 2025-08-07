<?php

namespace App\Entity;

use App\Repository\PlanetBuildingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanetBuildingsRepository::class)]
class PlanetBuildings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'buildings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planet $planet = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Building $building = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\OneToOne(inversedBy: 'planetBuildings', cascade: ['persist', 'remove'])]
    private ?QueueBuilding $queue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlanet(): ?Planet
    {
        return $this->planet;
    }

    public function setPlanet(?Planet $planet): static
    {
        $this->planet = $planet;

        return $this;
    }

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(?Building $building): static
    {
        $this->building = $building;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s on %s (%d)',
            $this->getBuilding()?->getName(),
            $this->getPlanet()?->getName(),
            $this->getLevel()
        );
    }

    public function getQueue(): ?QueueBuilding
    {
        return $this->queue;
    }

    public function setQueue(?QueueBuilding $queue): static
    {
        $this->queue = $queue;

        return $this;
    }
}
