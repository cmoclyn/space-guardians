<?php

namespace App\Service;

use App\DTO\ResourceDTO;
use App\Entity\Planet;
use App\Entity\PlanetResource;
use App\Entity\Resource;
use App\Helper\CalculateHelper;
use App\Repository\ResourceRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class ResourceService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ResourceRepository $resourceRepository,
        private CalculateHelper $calculateHelper,
    ) {}

    /**
     * @param Planet $planet
     * @return ResourceDTO[]
     */
    public function getResourcesProduction(Planet $planet): array
    {
        return $planet->getResources()->map(function (PlanetResource $planetResource) use ($planet): ResourceDTO {
            return new ResourceDTO(
                $planetResource,
                $this->calculateHelper->calculateProductionPerHour($planet, $planetResource->getResource()),
            );
        })->toArray();
    }

    public function getResourceProduction(Planet $planet, Resource $resource): ResourceDTO
    {
        $planetResource = $planet->getResource($resource);
        return new ResourceDTO($planetResource, $this->calculateHelper->calculateProductionPerHour($planet, $resource));
    }

    public function getResources(): array
    {
        return $this->resourceRepository->findAll();
    }


    public function getActualResource(Planet $planet, Resource $resource, DateTimeInterface $dateTime): float
    {
        $production = $this->calculateHelper->calculateProductionPerHour($planet, $resource);

        $planetResource = $planet->getResource($resource);

        $elapsedSeconds = $dateTime->getTimestamp() - $planetResource->getDate()?->getTimestamp();

        $produced = ($production / 3600) * $elapsedSeconds;
        return min($planetResource->getQuantity() + $produced, 50000); // TODO Use storage building
    }

    public function updatePlanetResources(Planet $planet, DateTimeInterface $date = new DateTime()): void
    {
        foreach ($planet->getResources() as $planetResource) {
            $planetResource->setQuantity($this->getActualResource($planet, $planetResource->getResource(), $date));
            $planetResource->setDate($date);
            $this->entityManager->persist($planetResource);
        }
        $this->entityManager->flush();
    }

}