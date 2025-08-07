<?php

namespace App\Service;

use App\DTO\BuildingDTO;
use App\DTO\PriceDTO;
use App\DTO\QueueBuildingDTO;
use App\Entity\Building;
use App\Entity\BuildingResource;
use App\Entity\Planet;
use App\Entity\PlanetBuildings;
use App\Entity\PlanetResource;
use App\Entity\PlayerExperience;
use App\Entity\QueueBuilding;
use App\Entity\Resource;
use App\Exception\NotEnoughResourceException;
use App\Repository\ExperienceRepository;
use App\Repository\PlayerRepository;
use App\Repository\QueueBuildingRepository;
use App\Repository\ResourceRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\UnexpectedValueException;

readonly class BuildingService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ResourceRepository $resourceRepository,
        private QueueBuildingRepository $queueBuildingRepository,
        private ResourceService $resourceService,
    ) {}

    public function build(Planet $planet, Building $building): void
    {
        // On récupère l'entité représentant la combinaison Planet/Bâtiment
        $buildingToImprove = $planet->getBuildings()->findFirst(
            function (int $index, PlanetBuildings $planetBuildings) use ($building): bool {
                return $planetBuildings->getBuilding() === $building;
            },
        );

        $this->entityManager->beginTransaction();

        try {
            if (null === $buildingToImprove) {
                $buildingToImprove = (new PlanetBuildings())
                    ->setBuilding($building)
                    ->setPlanet($planet)
                    ->setLevel(1);
                $this->entityManager->persist($buildingToImprove);
            }

            // On met à jour la quantité des ressources utilisées par ce bâtiment
            $buildingToImprove->getBuilding()?->getBasePrices()->map(
                function (BuildingResource $buildingResource) use ($planet) {
                    $this->resourceService->updatePlanetResources($planet, $buildingResource->getResource());
                },
            );

            $nextLevel = $buildingToImprove->getLevel() + 1;

            $queueBuilding = (new QueueBuilding())
                ->setPlanetBuildings($buildingToImprove)
                ->setStartedAt(new DateTimeImmutable())
                ->setFinishedAt(new DateTimeImmutable('tomorrow'));
            $this->entityManager->persist($queueBuilding);

            $costs = $this->getBuildingCosts($building, $nextLevel);
            foreach ($costs as $cost) {
                $resource = $this->resourceRepository->findOneBy(['name' => $cost->getResourceName()]);
                if (null === $resource) {
                    throw new UnexpectedValueException(sprintf('Resource %s not found', $cost->getResourceName()));
                }

                $planetResource = $planet->getResource($resource);
                if (null === $planetResource) {
                    throw new UnexpectedValueException(
                        sprintf('Resource %s not found on Planet', $cost->getResourceName()),
                    );
                }

                if ($planetResource->getQuantity() < $cost->getQuantity()) {
                    throw new NotEnoughResourceException(
                        $planetResource->getResource(),
                        $cost->getQuantity(),
                        $planetResource->getQuantity(),
                    );
                }

                $planetResource->setQuantity($planetResource->getQuantity() - $cost->getQuantity());
                $planetResource->setDate(new DateTimeImmutable('now'));
                $this->entityManager->persist($planetResource);
            }
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (NotEnoughResourceException $e) {
            $this->entityManager->rollback();
            throw $e;
        }
        // Add production experience (when building is finished)
//        $productionExperience = $this->experienceRepository->findOneBy(['name' => 'Production']);
//
//        $quantity = 0;
//        foreach ($building->getBasePrices() as $basePrice) {
//            $resource = $basePrice->getResource();
//            $quantity += $basePrice->getQuantity() * $nextLevel * $resource?->getCoef();
//        }

//        $experience = (new PlayerExperience())
//            ->setPlayer($player)
//            ->setQuantity($quantity)
//            ->setExperience($productionExperience);
//        $this->entityManager->persist($experience);

    }

    public function checkFinishedBuildings(): void
    {
        $finishedBuildings = $this->queueBuildingRepository->getFinishedBuildings();
        foreach ($finishedBuildings as $building) {
            $this->buildFinished($building);
        }
    }

    public function buildFinished(QueueBuilding $queueBuilding): void
    {
        $planetBuilding = $queueBuilding->getPlanetBuildings();

        if (null === $planetBuilding) {
            throw new \UnexpectedValueException('Planet building not found');
        }
        $finishedDate = $queueBuilding->getFinishedAt();

        $planet = $planetBuilding->getPlanet();
        $planetBuilding->getBuilding()?->getBasePrices()->map(
            function (BuildingResource $buildingResource) use ($planet, $finishedDate) {
                $this->resourceService->updatePlanetResources($planet, $buildingResource->getResource(), $finishedDate);
            },
        );

        $planetBuilding->setLevel($planetBuilding->getLevel() + 1);
        $planetBuilding->setQueue(null);
        $this->entityManager->persist($planetBuilding);
        $this->entityManager->remove($queueBuilding);
        $this->entityManager->flush();
    }

    /**
     * @param Planet $planet
     * @return BuildingDTO[]
     */
    public function getBuildingsDtoForPlanet(Planet $planet): array
    {
        return $planet->getBuildings()->map(function (PlanetBuildings $planetBuildings) use ($planet): BuildingDTO {
            $building = $planetBuildings->getBuilding();
            $level = $planetBuildings->getLevel();
            return new BuildingDTO($building, $level, $this->getBuildingCosts($building, $level + 1));
        })->toArray();
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

    public function getBuildingQueue(Planet $planet): ?QueueBuildingDTO
    {
        return $planet->getBuildings()->findFirst(
            function (int $index, PlanetBuildings $planetBuildings): bool {
                return $this->queueBuildingRepository->findOneBy([
                    'planetBuilding' => $planetBuildings,
                ]) ?? false;
            },
        );
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

    public function calculateMaxStorage(Building $building, Resource $resource, int $level): int
    {
        $nextLevelCost = $this->getBuildingCost($building, $resource, $level + 1);
        $marge = $nextLevelCost * 1.2;

        return $this->resourceService->roundToNiceNumber($marge);
    }
}