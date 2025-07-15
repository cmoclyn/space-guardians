<?php

namespace App\Service;

use App\DTO\ResourceDTO;
use App\Entity\Planet;
use App\Entity\PlanetResource;
use DateTime;

class ResourceService {

    /**
     * @param Planet $planet
     * @return ResourceDTO[]
     */
    public function getResourcesProduction(Planet $planet): array
    {
        return $planet->getResources()->map(function (PlanetResource $planetResource) use ($planet): ResourceDTO {
            return new ResourceDTO($planetResource);
        })->toArray();
    }

}