<?php

namespace App\Service;

use App\DTO\ResourceDTO;
use App\Entity\Building;
use App\Entity\Planet;
use App\Entity\PlanetBuildings;
use App\Entity\PlanetResource;
use App\Entity\Resource;
use App\Repository\BuildingRepository;
use App\Repository\ResourceRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\UnexpectedValueException;

readonly class ResourceService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ResourceRepository $resourceRepository,
        private BuildingRepository $buildingRepository,
    ) {}

    /**
     * @param Planet $planet
     * @return ResourceDTO[]
     */
    public function getResourcesProduction(Planet $planet): array
    {
        return $planet->getResources()->map(function (PlanetResource $planetResource) use ($planet): ResourceDTO {
            return new ResourceDTO($planetResource, $this->calculateProductionPerHour($planet, $planetResource->getResource()));
        })->toArray();
    }

    public function getResourceProduction(Planet $planet, Resource $resource): ResourceDTO
    {
        $planetResource = $planet->getResource($resource);
        return new ResourceDTO($planetResource, $this->calculateProductionPerHour($planet, $resource));
    }

    public function getResources(): array
    {
        return $this->resourceRepository->findAll();
    }

    public function calculateProductionPerHour(Planet $planet, Resource $resource): float
    {
        $buildingForResource = match ($resource->getName()) {
            'Titane' => $this->buildingRepository->findOneBy(['name' => 'Fonderie de titane']),
            'Deutérium' => $this->buildingRepository->findOneBy(['name' => 'Extracteur de deutérium']),
            default => throw new UnexpectedValueException($resource->getName()),
        };

        $planetBuildingForResource = $planet->getBuildings()->findFirst(
            function (int $index, PlanetBuildings $planetBuilding) use ($buildingForResource): bool {
                return $planetBuilding->getBuilding() === $buildingForResource;
            },
        );

        $baseProduction = 100;
        $scalingExponent = 1.5;

        $production = $baseProduction * ($planetBuildingForResource->getLevel() ** $scalingExponent);
        return $production * $resource->getCoef();
    }

    public function getActualResource(Planet $planet, Resource $resource, DateTimeInterface $dateTime): float
    {
        $production = $this->calculateProductionPerHour($planet, $resource);

        $planetResource = $planet->getResource($resource);

        $elapsedSeconds = $dateTime->getTimestamp() - $planetResource->getDate()?->getTimestamp();

        $produced = ($production / 3600) * $elapsedSeconds;
        return min($planetResource->getQuantity() + $produced, 50000); // TODO Use storage building
    }

    public function updatePlanetResources(Planet $planet, Resource $resource): void
    {
        $planetResource = $planet->getResource($resource);
        if (null === $planetResource) {
            return;
        }
        $date = new DateTime();
        $planetResource->setQuantity($this->getActualResource($planet, $resource, $date));
        $planetResource->setDate($date);
        $this->entityManager->persist($planetResource);
        $this->entityManager->flush();
    }
}