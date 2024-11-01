<?php

namespace App\Entity;

use App\Repository\GalaxyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GalaxyRepository::class)]
class Galaxy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, SolarSystem>
     */
    #[ORM\OneToMany(targetEntity: SolarSystem::class, mappedBy: 'galaxy', orphanRemoval: true)]
    private Collection $solarSystems;

    public function __construct()
    {
        $this->solarSystems = new ArrayCollection();
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
     * @return Collection<int, SolarSystem>
     */
    public function getSolarSystems(): Collection
    {
        return $this->solarSystems;
    }

    public function addSolarSystem(SolarSystem $solarSystem): static
    {
        if (!$this->solarSystems->contains($solarSystem)) {
            $this->solarSystems->add($solarSystem);
            $solarSystem->setGalaxy($this);
        }

        return $this;
    }

    public function removeSolarSystem(SolarSystem $solarSystem): static
    {
        if ($this->solarSystems->removeElement($solarSystem)) {
            // set the owning side to null (unless already changed)
            if ($solarSystem->getGalaxy() === $this) {
                $solarSystem->setGalaxy(null);
            }
        }

        return $this;
    }
}
