<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'players')]
    #[ORM\JoinColumn(nullable: false)]
    private User $owner;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $originalOwner;

    /**
     * @var Collection<int, Planet>
     */
    #[ORM\OneToMany(targetEntity: Planet::class, mappedBy: 'owner')]
    private Collection $planets;

    /**
     * @var Collection<int, ShipModel>
     */
    #[ORM\OneToMany(targetEntity: ShipModel::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $shipModels;

    /**
     * @var Collection<int, Ship>
     */
    #[ORM\OneToMany(targetEntity: Ship::class, mappedBy: 'owner')]
    private Collection $ships;

    /**
     * @var Collection<int, Fleet>
     */
    #[ORM\OneToMany(targetEntity: Fleet::class, mappedBy: 'owner')]
    private Collection $fleets;

    #[ORM\ManyToOne(inversedBy: 'players')]
    private ?GuildRank $guildRank = null;

    /**
     * @var Collection<int, PlayerTechnology>
     */
    #[ORM\OneToMany(targetEntity: PlayerTechnology::class, mappedBy: 'player', orphanRemoval: true)]
    private Collection $technologies;

    /**
     * @var Collection<int, PlayerExperience>
     */
    #[ORM\OneToMany(targetEntity: PlayerExperience::class, mappedBy: 'player', orphanRemoval: true)]
    private Collection $playerExperiences;

    public function __construct()
    {
        $this->planets = new ArrayCollection();
        $this->shipModels = new ArrayCollection();
        $this->ships = new ArrayCollection();
        $this->fleets = new ArrayCollection();
        $this->technologies = new ArrayCollection();
        $this->playerExperiences = new ArrayCollection();
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

    /**
     * @return Collection<int, Planet>
     */
    public function getPlanets(): Collection
    {
        return $this->planets;
    }

    public function addPlanet(Planet $planet): static
    {
        if (!$this->planets->contains($planet)) {
            $this->planets->add($planet);
            $planet->setOwner($this);
        }

        return $this;
    }

    public function removePlanet(Planet $planet): static
    {
        if ($this->planets->removeElement($planet)) {
            // set the owning side to null (unless already changed)
            if ($planet->getOwner() === $this) {
                $planet->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShipModel>
     */
    public function getShipModels(): Collection
    {
        return $this->shipModels;
    }

    public function addShipModel(ShipModel $shipModel): static
    {
        if (!$this->shipModels->contains($shipModel)) {
            $this->shipModels->add($shipModel);
            $shipModel->setOwner($this);
        }

        return $this;
    }

    public function removeShipModel(ShipModel $shipModel): static
    {
        if ($this->shipModels->removeElement($shipModel)) {
            // set the owning side to null (unless already changed)
            if ($shipModel->getOwner() === $this) {
                $shipModel->setOwner(null);
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
            $ship->setOwner($this);
        }

        return $this;
    }

    public function removeShip(Ship $ship): static
    {
        if ($this->ships->removeElement($ship)) {
            // set the owning side to null (unless already changed)
            if ($ship->getOwner() === $this) {
                $ship->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fleet>
     */
    public function getFleets(): Collection
    {
        return $this->fleets;
    }

    public function addFleet(Fleet $fleet): static
    {
        if (!$this->fleets->contains($fleet)) {
            $this->fleets->add($fleet);
            $fleet->setOwner($this);
        }

        return $this;
    }

    public function removeFleet(Fleet $fleet): static
    {
        if ($this->fleets->removeElement($fleet)) {
            // set the owning side to null (unless already changed)
            if ($fleet->getOwner() === $this) {
                $fleet->setOwner(null);
            }
        }

        return $this;
    }

    public function getGuildRank(): ?GuildRank
    {
        return $this->guildRank;
    }

    public function setGuildRank(?GuildRank $guildRank): static
    {
        $this->guildRank = $guildRank;

        return $this;
    }

    /**
     * @return Collection<int, PlayerTechnology>
     */
    public function getTechnologies(): Collection
    {
        return $this->technologies;
    }

    public function addTechnology(PlayerTechnology $technology): static
    {
        if (!$this->technologies->contains($technology)) {
            $this->technologies->add($technology);
            $technology->setPlayer($this);
        }

        return $this;
    }

    public function removeTechnology(PlayerTechnology $technology): static
    {
        if ($this->technologies->removeElement($technology)) {
            // set the owning side to null (unless already changed)
            if ($technology->getPlayer() === $this) {
                $technology->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PlayerExperience>
     */
    public function getPlayerExperiences(): Collection
    {
        return $this->playerExperiences;
    }

    public function addPlayerExperience(PlayerExperience $playerExperience): static
    {
        if (!$this->playerExperiences->contains($playerExperience)) {
            $this->playerExperiences->add($playerExperience);
            $playerExperience->setPlayer($this);
        }

        return $this;
    }

    public function removePlayerExperience(PlayerExperience $playerExperience): static
    {
        if ($this->playerExperiences->removeElement($playerExperience)) {
            // set the owning side to null (unless already changed)
            if ($playerExperience->getPlayer() === $this) {
                $playerExperience->setPlayer(null);
            }
        }

        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): Player
    {
        $this->owner = $owner;
        return $this;
    }

    public function getOriginalOwner(): User
    {
        return $this->originalOwner;
    }

    public function setOriginalOwner(User $originalOwner): Player
    {
        $this->originalOwner = $originalOwner;
        return $this;
    }
}
