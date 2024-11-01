<?php

namespace App\Entity;

use App\Repository\PlayerTechnologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerTechnologyRepository::class)]
class PlayerTechnology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'technologies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Technology $technology = null;

    #[ORM\Column]
    private ?int $level = null;

    /**
     * @var Collection<int, QueueTechnology>
     */
    #[ORM\OneToMany(targetEntity: QueueTechnology::class, mappedBy: 'playerTechnology', orphanRemoval: true)]
    private Collection $queues;

    public function __construct()
    {
        $this->queues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getTechnology(): ?Technology
    {
        return $this->technology;
    }

    public function setTechnology(?Technology $technology): static
    {
        $this->technology = $technology;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection<int, QueueTechnology>
     */
    public function getQueues(): Collection
    {
        return $this->queues;
    }

    public function addQueue(QueueTechnology $queue): static
    {
        if (!$this->queues->contains($queue)) {
            $this->queues->add($queue);
            $queue->setPlayerTechnology($this);
        }

        return $this;
    }

    public function removeQueue(QueueTechnology $queue): static
    {
        if ($this->queues->removeElement($queue)) {
            // set the owning side to null (unless already changed)
            if ($queue->getPlayerTechnology() === $this) {
                $queue->setPlayerTechnology(null);
            }
        }

        return $this;
    }
}
