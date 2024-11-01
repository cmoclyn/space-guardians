<?php

namespace App\Entity;

use App\Repository\BuildingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildingRepository::class)]
class Building
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'buildings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BuildingType $type = null;

    /**
     * @var Collection<int, BuildingResource>
     */
    #[ORM\OneToMany(targetEntity: BuildingResource::class, mappedBy: 'building', orphanRemoval: true)]
    private Collection $basePrices;

    public function __construct()
    {
        $this->basePrices = new ArrayCollection();
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

    public function getType(): ?BuildingType
    {
        return $this->type;
    }

    public function setType(?BuildingType $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, BuildingResource>
     */
    public function getBasePrices(): Collection
    {
        return $this->basePrices;
    }

    public function addBasePrice(BuildingResource $basePrice): static
    {
        if (!$this->basePrices->contains($basePrice)) {
            $this->basePrices->add($basePrice);
            $basePrice->setBuilding($this);
        }

        return $this;
    }

    public function removeBasePrice(BuildingResource $basePrice): static
    {
        if ($this->basePrices->removeElement($basePrice)) {
            // set the owning side to null (unless already changed)
            if ($basePrice->getBuilding() === $this) {
                $basePrice->setBuilding(null);
            }
        }

        return $this;
    }
}
