<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, PlayerExperience>
     */
    #[ORM\OneToMany(targetEntity: PlayerExperience::class, mappedBy: 'experience', orphanRemoval: true)]
    private Collection $playerExperiences;

    public function __construct()
    {
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
            $playerExperience->setExperience($this);
        }

        return $this;
    }

    public function removePlayerExperience(PlayerExperience $playerExperience): static
    {
        if ($this->playerExperiences->removeElement($playerExperience)) {
            // set the owning side to null (unless already changed)
            if ($playerExperience->getExperience() === $this) {
                $playerExperience->setExperience(null);
            }
        }

        return $this;
    }
}
