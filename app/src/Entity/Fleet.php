<?php

namespace App\Entity;

use App\Repository\FleetRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FleetRepository::class)]
class Fleet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'fleets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $owner = null;

    #[ORM\Column]
    private ?int $positionX = null;

    #[ORM\Column]
    private ?int $positionY = null;

    /**
     * @var Collection<int, Ship>
     */
    #[ORM\OneToMany(targetEntity: Ship::class, mappedBy: 'fleet')]
    private Collection $ships;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $destinationX = null;

    #[ORM\Column(nullable: true)]
    private ?int $destinationY = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $departureTime = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $arrivalTime = null;

    #[ORM\Column]
    private ?bool $isMoving = null;

    public function __construct()
    {
        $this->ships = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $ship->setFleet($this);
        }

        return $this;
    }

    public function removeShip(Ship $ship): static
    {
        if ($this->ships->removeElement($ship)) {
            // set the owning side to null (unless already changed)
            if ($ship->getFleet() === $this) {
                $ship->setFleet(null);
            }
        }

        return $this;
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

    public function getDestinationX(): ?int
    {
        return $this->destinationX;
    }

    public function setDestinationX(?int $destinationX): static
    {
        $this->destinationX = $destinationX;

        return $this;
    }

    public function getDestinationY(): ?int
    {
        return $this->destinationY;
    }

    public function setDestinationY(?int $destinationY): static
    {
        $this->destinationY = $destinationY;

        return $this;
    }

    public function getDepartureTime(): ?\DateTimeImmutable
    {
        return $this->departureTime;
    }

    public function setDepartureTime(?\DateTimeImmutable $departureTime): static
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    public function getArrivalTime(): ?\DateTimeImmutable
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime(?\DateTimeImmutable $arrivalTime): static
    {
        $this->arrivalTime = $arrivalTime;

        return $this;
    }

    public function isMoving(): ?bool
    {
        return $this->isMoving;
    }

    public function setMoving(bool $isMoving): static
    {
        $this->isMoving = $isMoving;

        return $this;
    }

    public function calculateFleetSpeed(): ?float
    {
        $minimumSpeed = null;
        // Assume speed is returned in units per second
        foreach($this->ships as $ship){
            $minimumSpeed = min(array_filter([$ship->getModel()->getSpeed(), $minimumSpeed]));
        }
        return $minimumSpeed;
    }

    public function updatePosition(): void
    {
        // Step 1: Check if the fleet is in transit
        if (!$this->isMoving) {
            return; // Fleet is stationary, no need to update
        }

        // Step 2: Calculate time elapsed since departure (in seconds)
        $now = new DateTime();
        $elapsedTime = $this->departureTime->getTimestamp() - $now->getTimestamp(); // in seconds

        // Step 3: Calculate fleet speed
        $fleetSpeed = $this->calculateFleetSpeed(); // units per second

        // Step 4: Calculate the total distance to the destination
        $totalDistance = sqrt((($this->destinationX - $this->positionX) ** 2) + (($this->destinationY - $this->positionY) ** 2));

        // Step 5: Calculate the distance the fleet has traveled
        $distanceTraveled = $fleetSpeed * $elapsedTime;

        // Step 6: Check if the fleet has reached (or surpassed) its destination
        if ($distanceTraveled >= $totalDistance) {
            // The fleet has reached its destination
            $this->positionX = $this->destinationX;
            $this->positionY = $this->destinationY;
            $this->isMoving = false; // Fleet stops moving
        } else {
            // The fleet is still in transit, calculate new position
            $progressRatio = $distanceTraveled / $totalDistance;

            // Update x and y positions based on progress
            $this->positionX += ($this->destinationX - $this->positionX) * $progressRatio;
            $this->positionY += ($this->destinationY - $this->positionY) * $progressRatio;
        }
    }
}
