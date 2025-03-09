<?php

namespace App\EventSubscriber;

use App\Entity\Player;
use App\Repository\PlanetRepository;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PlayerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private PlanetRepository $planetRepository
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'onPlayerCreated',
        ];
    }

    public function onPlayerCreated(BeforeEntityPersistedEvent $event): void
    {
        $player = $event->getEntityInstance();
        if (!($player instanceof Player)) {
            return;
        }

        $player->addPlanet($this->planetRepository->getRandomPlanetWithoutOwner());
    }
}