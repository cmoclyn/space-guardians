<?php

namespace App\Service;

use App\Entity\Planet;
use App\Entity\PlanetResource;
use App\Entity\Player;
use App\Repository\PlanetRepository;
use App\Repository\ResourceRepository;
use DateTime;

readonly class PlanetService
{
    public function __construct(
        private PlanetRepository $planetRepository,
        private ResourceRepository $resourceRepository,
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

        foreach($this->resourceRepository->findAll() as $resource) {
            $planetResource = new PlanetResource();
            $planetResource->setResource($resource);
            $planetResource->setDate(new DateTime());
            $planetResource->setPlanet($planet);
            $planetResource->setQuantity(100);

            $planet->addResource($planetResource);
        }

        return true;
    }
}