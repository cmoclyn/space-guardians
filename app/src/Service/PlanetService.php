<?php

namespace App\Service;

use App\Entity\Planet;
use App\Entity\PlanetBuildings;
use App\Entity\PlanetResource;
use App\Entity\Player;
use App\Repository\BuildingRepository;
use App\Repository\PlanetRepository;
use App\Repository\ResourceRepository;
use DateTime;

readonly class PlanetService
{
    public function __construct(
        private PlanetRepository $planetRepository,
        private ResourceRepository $resourceRepository,
        private BuildingRepository $buildingRepository,
    ) {}

    /**
     * @return Planet[]
     */
    public function suggestPlanetsToColonize(): array
    {
        return $this->planetRepository->findBy(['owner' => null], limit: 3);
    }

    public function colonizePlanet(Player $player, Planet $planet): bool
    {
        if ($planet->getOwner() !== null) {
            return false;
        }

        $player->addPlanet($planet);
        $planet->setOwner($player);

        $this->initResources($planet);
        $this->initBuildings($planet);

        return true;
    }

    private function initResources(Planet $planet): void
    {
        foreach ($this->resourceRepository->findAll() as $resource) {
            $planetResource = new PlanetResource();
            $planetResource->setResource($resource);
            $planetResource->setDate(new DateTime());
            $planetResource->setPlanet($planet);
            $planetResource->setQuantity(100);

            $planet->addResource($planetResource);
        }
    }

    private function initBuildings(Planet $planet): void
    {
        foreach ($this->buildingRepository->findAll() as $building) {
            $planetBuilding = new PlanetBuildings();
            $planetBuilding->setBuilding($building);
            $planetBuilding->setPlanet($planet);
            $planetBuilding->setLevel(0);

            $planet->addBuilding($planetBuilding);
        }
    }
}