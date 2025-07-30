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
                ->setPlanetBuilding($buildingToImprove->setLevel($nextLevel))
                ->setStartedAt(new DateTimeImmutable())
                ->setFinishedAt(new DateTimeImmutable('tomorrow'));
            $this->entityManager->persist($queueBuilding);

            $costs = $this->getBuildingCost($building, $nextLevel);
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

    /**
     * @param Planet $planet
     * @return BuildingDTO[]
     */
    public function getBuildingsDtoForPlanet(Planet $planet): array
    {
        return $planet->getBuildings()->map(function (PlanetBuildings $planetBuildings) use ($planet): BuildingDTO {
            $building = $planetBuildings->getBuilding();
            $level = $planetBuildings->getLevel();
            return new BuildingDTO($building, $level, $this->getBuildingCost($building, $level + 1));
        })->toArray();
    }

    /**
     * @param Building $building
     * @param int $level
     * @return PriceDTO[]
     */
    private function getBuildingCost(Building $building, int $level): array
    {
        return $building->getBasePrices()->map(function (BuildingResource $buildingResource) use ($level): PriceDTO {
            $growth = 1.6 + log($level + 1); // croissance de plus en plus raide
            $baseCost = $buildingResource->getQuantity();
            $rarity = $buildingResource->getResource()->getCoef();
            $cost = round($baseCost * ($growth ** ($level)) / $rarity);
            return new PriceDTO($buildingResource->getResource()->getName(), $cost);
        })->toArray();
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
}