<?php

namespace App\EventListener;

use App\Event\SolarSystemCreatedEvent;
use App\Service\Generator\SolarSystemGenerator;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class SolarSystemListener
{
    public function __construct(
        private SolarSystemGenerator $solarSystemGenerator
    ) {}
    #[AsEventListener]
    public function onSolarSystemCreated(SolarSystemCreatedEvent $event): void
    {
        $solarSystem = $event->getSolarSystem();

    }
}