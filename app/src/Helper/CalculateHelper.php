<?php

namespace App\Helper;

use App\DTO\PriceDTO;
use App\Entity\Building;
use App\Entity\BuildingResource;
use App\Entity\Planet;
use App\Entity\PlanetBuildings;
use App\Entity\Resource;
use App\Repository\BuildingRepository;
use App\Repository\ResourceRepository;
use UnexpectedValueException;

readonly class CalculateHelper
{

    public function __construct(
        private ResourceRepository $resourceRepository,
        private BuildingRepository $buildingRepository,
        private NumberHelper $numberHelper,
    ) {}

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

    public function calculateBuildTime(Building $building, mixed $level): int
    {
        $resources = $this->resourceRepository->findAll();
        $totalCost = 0;
        foreach ($resources as $resource) {
            $totalCost += $this->getBuildingCost($building, $resource, $level);
        }
        return ($totalCost * 1.5) ** 0.6;
    }

    public function calculateMaxStorage(Resource $resource, int $level): int
    {
        $building = $this->buildingRepository->findOneBy(['name' => 'Entrepôt']);
        $nextLevelCost = $this->getBuildingCost($building, $resource, $level + 1);
        $marge = $nextLevelCost * 1.2;

        return $this->numberHelper->roundToNiceNumber($marge);
    }


    /**
     * @param Building $building
     * @param int $level
     * @return PriceDTO[]
     */
    public function getBuildingCosts(Building $building, int $level): array
    {
        $costs = [];
        $resources = $this->resourceRepository->findAll();

        /** @var Resource $resource */
        foreach ($resources as $resource) {
            $cost = $this->getBuildingCost($building, $resource, $level + 1);
            $costs[] = new PriceDTO($resource->getName(), $cost);
        }
        return $costs;
    }

    public function getBuildingCost(Building $building, Resource $resource, int $level): int
    {
        $buildingResource = $building->getBasePrices()->findFirst(
            function (int $index, BuildingResource $buildingResource) use ($resource) {
                return $buildingResource->getResource() === $resource;
            },
        );

        if (null === $buildingResource) {
            throw new \UnexpectedValueException('Building resource not found');
        }

        $baseCost = $buildingResource->getQuantity();
        $cost = ($baseCost * $level * 1.5) ** 2;
        $scaling = 0.01 * (1.35 ** $level);
        $rarity = $resource->getCoef();
        return round(($cost * $scaling) / ($rarity ** ($level)));
    }
}