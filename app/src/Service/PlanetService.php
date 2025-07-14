<?php

namespace App\Service;

use App\Entity\Planet;
use App\Repository\PlanetRepository;

class PlanetService {
    public function __construct(private readonly PlanetRepository $planetRepository) {}

    /**
     * @return Planet[]
     */
    public function suggestPlanetsToColonize(): array
    {
        return $this->planetRepository->findBy(['owner' => null], limit: 3);
    }

    public function colonizePlanet(Planet $planet): bool
    {
        if ($planet->getOwner() !== null) {
            return false;
        }

        $planet->setOwner();
        return true;
    }
}