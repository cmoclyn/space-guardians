<?php

namespace App\Entity;

use App\Repository\TechnologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TechnologyRepository::class)]
class Technology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'technologies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TechnologyType $type = null;

    /**
     * @var Collection<int, ShipModel>
     */
    #[ORM\ManyToMany(targetEntity: ShipModel::class, mappedBy: 'technologies')]
    private Collection $shipModels;

    /**
     * @var Collection<int, TechnologyResource>
     */
    #[ORM\OneToMany(targetEntity: TechnologyResource::class, mappedBy: 'technology', orphanRemoval: true)]
    private Collection $prices;

    public function __construct()
    {
        $this->shipModels = new ArrayCollection();
        $this->prices = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?TechnologyType
    {
        return $this->type;
    }

    public function setType(?TechnologyType $type): static
    {
        $this->type = $type;

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
            $shipModel->addTechnology($this);
        }

        return $this;
    }

    public function removeShipModel(ShipModel $shipModel): static
    {
        if ($this->shipModels->removeElement($shipModel)) {
            $shipModel->removeTechnology($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, TechnologyResource>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(TechnologyResource $price): static
    {
        if (!$this->prices->contains($price)) {
            $this->prices->add($price);
            $price->setTechnology($this);
        }

        return $this;
    }

    public function removePrice(TechnologyResource $price): static
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getTechnology() === $this) {
                $price->setTechnology(null);
            }
        }

        return $this;
    }
}
