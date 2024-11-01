<?php

namespace App\EventSubscriber;

use App\Entity\Galaxy;
use App\Service\Generator\GalaxyGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class GalaxySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private GalaxyGenerator $galaxyGenerator
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => 'onGalaxyCreated',
        ];
    }

    public function onGalaxyCreated(AfterEntityPersistedEvent $event): void
    {
        $galaxy = $event->getEntityInstance();
        if (!($galaxy instanceof Galaxy)) {
            return;
        }

        $this->galaxyGenerator->generateGalaxy($galaxy, 200);
    }
}