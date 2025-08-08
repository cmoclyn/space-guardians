<?php

namespace App\EventSubscriber;

use App\Entity\Building;
use App\Entity\PlanetBuildings;
use App\Repository\PlanetRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class BuildingSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private PlanetRepository $planetRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'beforeBuildingCreated',
            AfterEntityPersistedEvent::class => 'afterBuildingCreated',
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

    public function afterBuildingCreated(AfterEntityPersistedEvent $event): void
    {
        $building = $event->getEntityInstance();
        if (!($building instanceof Building)) {
            return;
        }

        $planets = $this->planetRepository->getPlanetsWithOwner();
        foreach ($planets as $planet) {
            $planetBuilding = new PlanetBuildings();
            $planetBuilding->setBuilding($building);
            $planetBuilding->setPlanet($planet);
            $planetBuilding->setLevel(0);
            $planet->addBuilding($planetBuilding);
            $this->entityManager->persist($planetBuilding);
        }
        $this->entityManager->flush();
    }
}