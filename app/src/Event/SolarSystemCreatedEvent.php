<?php

namespace App\Event;

use App\Entity\SolarSystem;
use Symfony\Contracts\EventDispatcher\Event;

class SolarSystemCreatedEvent extends Event
{
    public function __construct(private readonly SolarSystem $solarSystem)
    {
    }

    public function getSolarSystem(): SolarSystem
    {
        return $this->solarSystem;
    }
}