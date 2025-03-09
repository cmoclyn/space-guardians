<?php

namespace App\EventSubscriber;

use App\Entity\Building;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class BuildingSubscriber implements EventSubscriberInterface
{
    public function __construct() {}

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'beforeBuildingCreated',
        ];
    }

    public function beforeBuildingCreated(BeforeEntityPersistedEvent $event): void
    {
        $building = $event->getEntityInstance();
        if (!($building instanceof Building)) {
            return;
        }

        foreach ($building->getBasePrices() as $basePrice) {
            $basePrice->setBuilding($building);
        }
    }
}