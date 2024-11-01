<?php

namespace App\Entity;

use App\Repository\ShipRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShipRepository::class)]
class Ship
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShipModel $model = null;

    #[ORM\ManyToOne(inversedBy: 'ships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $owner = null;

    #[ORM\ManyToOne(inversedBy: 'ships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fleet $fleet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ShipModel
    {
        return $this->model;
    }

    public function setModel(ShipModel $model): static
    {
        $this->model = $model;

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

    public function getFleet(): ?Fleet
    {
        return $this->fleet;
    }

    public function setFleet(?Fleet $fleet): static
    {
        $this->fleet = $fleet;

        return $this;
    }
}
