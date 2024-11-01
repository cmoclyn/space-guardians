<?php

namespace App\Entity;

use App\Repository\ShipModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShipModelRepository::class)]
class ShipModel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'shipModels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShipCategory $category = null;

    /**
     * @var Collection<int, Technology>
     */
    #[ORM\ManyToMany(targetEntity: Technology::class, inversedBy: 'shipModels')]
    private Collection $technologies;

    #[ORM\ManyToOne(inversedBy: 'shipModels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $owner = null;

    /**
     * @var Collection<int, ShipModelResource>
     */
    #[ORM\OneToMany(targetEntity: ShipModelResource::class, mappedBy: 'shipModel', orphanRemoval: true)]
    private Collection $prices;

    /**
     * @var Collection<int, Ship>
     */
    #[ORM\OneToMany(targetEntity: Ship::class, mappedBy: 'model')]
    private Collection $ships;

    /**
     * @var Collection<int, QueueShip>
     */
    #[ORM\OneToMany(targetEntity: QueueShip::class, mappedBy: 'shipModel', orphanRemoval: true)]
    private Collection $queues;

    #[ORM\Column]
    private ?float $speed = null;

    public function __construct()
    {
        $this->technologies = new ArrayCollection();
        $this->prices = new ArrayCollection();
        $this->ships = new ArrayCollection();
        $this->queues = new ArrayCollection();
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

    public function getCategory(): ?ShipCategory
    {
        return $this->category;
    }

    public function setCategory(?ShipCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Technology>
     */
    public function getTechnologies(): Collection
    {
        return $this->technologies;
    }

    public function addTechnology(Technology $technology): static
    {
        if (!$this->technologies->contains($technology)) {
            $this->technologies->add($technology);
        }

        return $this;
    }

    public function removeTechnology(Technology $technology): static
    {
        $this->technologies->removeElement($technology);

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

    /**
     * @return Collection<int, ShipModelResource>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(ShipModelResource $price): static
    {
        if (!$this->prices->contains($price)) {
            $this->prices->add($price);
            $price->setShipModel($this);
        }

        return $this;
    }

    public function removePrice(ShipModelResource $price): static
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getShipModel() === $this) {
                $price->setShipModel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ship>
     */
    public function getShips(): Collection
    {
        return $this->ships;
    }

    public function addShip(Ship $ship): static
    {
        if (!$this->ships->contains($ship)) {
            $this->ships->add($ship);
            $ship->setModel($this);
        }

        return $this;
    }

    public function removeShip(Ship $ship): static
    {
        if ($this->ships->removeElement($ship)) {
            // set the owning side to null (unless already changed)
            if ($ship->getModel() === $this) {
                $ship->setModel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QueueShip>
     */
    public function getQueues(): Collection
    {
        return $this->queues;
    }

    public function addQueue(QueueShip $queue): static
    {
        if (!$this->queues->contains($queue)) {
            $this->queues->add($queue);
            $queue->setShipModel($this);
        }

        return $this;
    }

    public function removeQueue(QueueShip $queue): static
    {
        if ($this->queues->removeElement($queue)) {
            // set the owning side to null (unless already changed)
            if ($queue->getShipModel() === $this) {
                $queue->setShipModel(null);
            }
        }

        return $this;
    }

    public function getSpeed(): ?float
    {
        return $this->speed;
    }

    public function setSpeed(float $speed): static
    {
        $this->speed = $speed;

        return $this;
    }
}
