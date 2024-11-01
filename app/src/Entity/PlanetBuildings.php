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

    /**
     * @var Collection<int, QueueBuilding>
     */
    #[ORM\OneToMany(targetEntity: QueueBuilding::class, mappedBy: 'planetBuilding', orphanRemoval: true)]
    private Collection $queues;

    public function __construct()
    {
        $this->queues = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, QueueBuilding>
     */
    public function getQueues(): Collection
    {
        return $this->queues;
    }

    public function addQueue(QueueBuilding $queue): static
    {
        if (!$this->queues->contains($queue)) {
            $this->queues->add($queue);
            $queue->setPlanetBuilding($this);
        }

        return $this;
    }

    public function removeQueue(QueueBuilding $queue): static
    {
        if ($this->queues->removeElement($queue)) {
            // set the owning side to null (unless already changed)
            if ($queue->getPlanetBuilding() === $this) {
                $queue->setPlanetBuilding(null);
            }
        }

        return $this;
    }
}
