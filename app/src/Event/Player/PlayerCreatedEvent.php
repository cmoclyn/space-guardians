<?php

namespace App\Event\Player;

use App\Entity\Player;
use Symfony\Contracts\EventDispatcher\Event;

class PlayerCreatedEvent extends Event
{
    public function __construct(private readonly Player $player)
    {
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }
}