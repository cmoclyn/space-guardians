<?php

namespace App\Entity;

use App\Repository\PlanetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanetRepository::class)]
class Planet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $positionX = null;

    #[ORM\Column]
    private ?int $positionY = null;

    #[ORM\ManyToOne(inversedBy: 'planets')]
    private ?Player $owner = null;

    #[ORM\ManyToOne(inversedBy: 'planets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SolarSystem $solarSystem = null;

    /**
     * @var Collection<int, PlanetBuildings>
     */
    #[ORM\OneToMany(targetEntity: PlanetBuildings::class, mappedBy: 'planet', orphanRemoval: true)]
    private Collection $buildings;

    /**
     * @var Collection<int, QueueShip>
     */
    #[ORM\OneToMany(targetEntity: QueueShip::class, mappedBy: 'planet', orphanRemoval: true)]
    private Collection $queueShips;

    /**
     * @var Collection<int, PlanetResource>
     */
    #[ORM\OneToMany(targetEntity: PlanetResource::class, mappedBy: 'planet', orphanRemoval: true)]
    private Collection $resources;

    public function __construct()
    {
        $this->buildings = new ArrayCollection();
        $this->queueShips = new ArrayCollection();
        $this->resources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPositionX(): ?int
    {
        return $this->positionX;
    }

    public function setPositionX(int $positionX): static
    {
        $this->positionX = $positionX;

        return $this;
    }

    public function getPositionY(): ?int
    {
        return $this->positionY;
    }

    public function setPositionY(int $positionY): static
    {
        $this->positionY = $positionY;

        return $this;
    }

    public function getOwner(): ?Player
    {
        return $this->owner;
    }

    public function setOwner(?Player $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSolarSystem(): ?SolarSystem
    {
        return $this->solarSystem;
    }

    public function setSolarSystem(?SolarSystem $solarSystem): static
    {
        $this->solarSystem = $solarSystem;

        return $this;
    }

    /**
     * @return Collection<int, PlanetBuildings>
     */
    public function getBuildings(): Collection
    {
        return $this->buildings;
    }

    public function addBuilding(PlanetBuildings $building): static
    {
        if (!$this->buildings->contains($building)) {
            $this->buildings->add($building);
            $building->setPlanet($this);
        }

        return $this;
    }

    public function removeBuilding(PlanetBuildings $building): static
    {
        if ($this->buildings->removeElement($building)) {
            // set the owning side to null (unless already changed)
            if ($building->getPlanet() === $this) {
                $building->setPlanet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QueueShip>
     */
    public function getQueueShips(): Collection
    {
        return $this->queueShips;
    }

    public function addQueueShip(QueueShip $queueShip): static
    {
        if (!$this->queueShips->contains($queueShip)) {
            $this->queueShips->add($queueShip);
            $queueShip->setPlanet($this);
        }

        return $this;
    }

    public function removeQueueShip(QueueShip $queueShip): static
    {
        if ($this->queueShips->removeElement($queueShip)) {
            // set the owning side to null (unless already changed)
            if ($queueShip->getPlanet() === $this) {
                $queueShip->setPlanet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PlanetResource>
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(PlanetResource $resource): static
    {
        if (!$this->resources->contains($resource)) {
            $this->resources->add($resource);
            $resource->setPlanet($this);
        }

        return $this;
    }

    public function removeResource(PlanetResource $resource): static
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getPlanet() === $this) {
                $resource->setPlanet(null);
            }
        }

        return $this;
    }
}
