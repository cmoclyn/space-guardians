<?php

namespace App\Entity;

use App\Repository\ShipCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShipCategoryRepository::class)]
class ShipCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, ShipModel>
     */
    #[ORM\OneToMany(targetEntity: ShipModel::class, mappedBy: 'category')]
    private Collection $shipModels;

    public function __construct()
    {
        $this->shipModels = new ArrayCollection();
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
            $shipModel->setCategory($this);
        }

        return $this;
    }

    public function removeShipModel(ShipModel $shipModel): static
    {
        if ($this->shipModels->removeElement($shipModel)) {
            // set the owning side to null (unless already changed)
            if ($shipModel->getCategory() === $this) {
                $shipModel->setCategory(null);
            }
        }

        return $this;
    }
}
