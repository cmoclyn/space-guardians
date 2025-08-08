<?php

namespace App\Service;

use App\DTO\BuildingDTO;
use App\DTO\QueueBuildingDTO;
use App\Entity\Building;
use App\Entity\BuildingResource;
use App\Entity\Planet;
use App\Entity\PlanetBuildings;
use App\Entity\QueueBuilding;
use App\Exception\NotEnoughResourceException;
use App\Exception\QueueIsBusyException;
use App\Helper\CalculateHelper;
use App\Repository\QueueBuildingRepository;
use App\Repository\ResourceRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use UnexpectedValueException;

readonly class BuildingService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ResourceRepository $resourceRepository,
        private QueueBuildingRepository $queueBuildingRepository,
        private ResourceService $resourceService,
        private CalculateHelper $calculateHelper,
    ) {}

    public function isBuildingQueueBusy(Planet $planet): bool
    {
        return $planet->getActualBuildingQueue() !== null;
    }

    public function build(Planet $planet, Building $building): void
    {
        if($this->isBuildingQueueBusy($planet)){
            throw new QueueIsBusyException($planet);
        }
        // On récupère l'entité représentant la combinaison Planet/Bâtiment
        $buildingToImprove = $planet->getBuildings()->findFirst(
            function (int $index, PlanetBuildings $planetBuildings) use ($building): bool {
                return $planetBuildings->getBuilding() === $building;
            },
        );

        $this->entityManager->beginTransaction();

        try {
            // On met à jour la quantité des ressources de cette planète
            $this->resourceService->updatePlanetResources($planet);

            $nextLevel = $buildingToImprove->getLevel() + 1;
            $buildingTime = $this->calculateHelper->calculateBuildTime($building, $nextLevel);

            $startedDate = new DateTime();
            $finishedDate = clone $startedDate;
            $finishedDate->modify("+{$buildingTime} seconds");

            $queueBuilding = (new QueueBuilding())
                ->setPlanetBuildings($buildingToImprove)
                ->setStartedAt(DateTimeImmutable::createFromMutable($startedDate))
                ->setFinishedAt(DateTimeImmutable::createFromMutable($finishedDate));
            $this->entityManager->persist($queueBuilding);

            $costs = $this->calculateHelper->getBuildingCosts($building, $nextLevel);
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
        $this->resourceService->updatePlanetResources($planet, $finishedDate);

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
            return new BuildingDTO(
                $building,
                $level,
                $this->calculateHelper->getBuildingCosts($building, $level + 1),
                $this->calculateHelper->calculateBuildTime($building, $level + 1),
            );
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