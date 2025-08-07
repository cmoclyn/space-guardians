<?php

namespace App\Service;

use App\Entity\Building;
use App\Repository\ResourceRepository;

class ChartService
{
    private const MAX_LEVEL = 20;
    private const COLORS = ['red', 'green', 'blue'];

    public function __construct(
        private readonly ResourceRepository $resourceRepository,
        private readonly BuildingService $buildingService,
        private readonly ResourceService $resourceService,
    ) {}

    public function getCostsData(Building $building): array
    {
        $resources = $this->resourceRepository->findAll();

        $data = [
            'labels' => range(1, self::MAX_LEVEL),
            'datasets' => [],
        ];

        foreach ($resources as $index => $resource) {
            $data['datasets'][] = [
                'label' => $resource->getName(),
                'data' => array_map(
                    fn(int $level) => $this->buildingService->getBuildingCost($building, $resource, $level),
                    range(1, self::MAX_LEVEL),
                ),
                'borderColor' => self::COLORS[$index],
                'fill' => false,
            ];
        }

        return $data;
    }

    public function getConstructionTimeData(Building $building): array
    {
        return [
            'labels' => range(1, self::MAX_LEVEL),
            'datasets' => [
                [
                    'label' => 'Temps de construction (s)',
                    'data' => array_map(fn($level) => $this->buildingService->calculateBuildTime($building, $level),
                        range(1, self::MAX_LEVEL)),
                    'borderColor' => 'green',
                    'fill' => false,
                ],
            ],
        ];
    }

    public function getProductionData(Building $building): ?array
    {
        if ($building->getType()?->getName() !== 'Ressource') {
            return null;
        }

        return [
            'labels' => range(1, self::MAX_LEVEL),
            'datasets' => [
                [
                    'label' => 'Production par heure',
                    'data' => array_map(
                        fn($level) => $this->resourceService->calculateProductionForBuilding($building, $level),
                        range(1, self::MAX_LEVEL),
                    ),
                    'borderColor' => 'orange',
                    'fill' => false,
                ],
            ],
        ];
    }

    public function getStorageData(Building $building): ?array
    {
        if ($building->getType()?->getName() !== 'Ressource') {
            return null;
        }

        $resources = $this->resourceRepository->findAll();

        $data = [
            'labels' => range(1, self::MAX_LEVEL),
            'datasets' => [],
        ];

        foreach ($resources as $index => $resource) {
            $data['datasets'][] = [
                'label' => $resource->getName(),
                'data' => array_map(
                    fn(int $level) => $this->buildingService->calculateMaxStorage($building, $resource, $level),
                    range(1, self::MAX_LEVEL),
                ),
                'borderColor' => self::COLORS[$index],
                'fill' => false,
            ];
        }

        return $data;
    }
}