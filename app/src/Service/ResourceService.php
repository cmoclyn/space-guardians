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
            return new ResourceDTO(
                $planetResource,
                $this->calculateProductionPerHour($planet, $planetResource->getResource()),
            );
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

        return $this->calculateProductionForBuilding(
            $planetBuildingForResource->getBuilding(),
            $planetBuildingForResource->getLevel(),
        );
    }

    public function calculateProductionForBuilding(Building $building, int $level): float
    {
        /** @var Resource $resource */
        $resource = match ($building->getName()) {
            'Fonderie de titane' => $this->resourceRepository->findOneBy(['name' => 'Titane']),
            'Extracteur de deutérium' => $this->resourceRepository->findOneBy(['name' => 'Deutérium']),
            default => throw new UnexpectedValueException($building->getName()),
        };

        $baseProduction = 100;
        $production = ($baseProduction * $level * 0.75) ** 2;
        $production *= (1 / $resource->getCoef()) ** 0.6;

        return round($production);
    }

    public function getActualResource(Planet $planet, Resource $resource, DateTimeInterface $dateTime): float
    {
        $production = $this->calculateProductionPerHour($planet, $resource);

        $planetResource = $planet->getResource($resource);

        $elapsedSeconds = $dateTime->getTimestamp() - $planetResource->getDate()?->getTimestamp();

        $produced = ($production / 3600) * $elapsedSeconds;
        return min($planetResource->getQuantity() + $produced, 50000); // TODO Use storage building
    }

    public function updatePlanetResources(
        Planet $planet,
        Resource $resource,
        DateTimeInterface $date = new DateTime(),
    ): void {
        $planetResource = $planet->getResource($resource);
        if (null === $planetResource) {
            return;
        }
        $planetResource->setQuantity($this->getActualResource($planet, $resource, $date));
        $planetResource->setDate($date);
        $this->entityManager->persist($planetResource);
        $this->entityManager->flush();
    }

    public function roundToNiceNumber(float $value): int
    {
        if ($value <= 0) {
            return 0;
        }

        // Trouve la puissance de 10 la plus proche
        $exponent = floor(log10($value));
        $base = 10 ** $exponent;

        // Choix de jolis multiples
        $multipliers = [1, 2, 2.5, 5, 7.5, 10];

        foreach ($multipliers as $m) {
            if ($value <= $m * $base) {
                return (int)($m * $base);
            }
        }

        // Sinon on arrondit au multiple de 10 supérieur
        return (int)(10 * $base);
    }
}