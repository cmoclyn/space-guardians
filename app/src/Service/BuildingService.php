<?php

namespace App\Service;

use App\DTO\BuildingDTO;
use App\DTO\PriceDTO;
use App\Entity\Building;
use App\Entity\BuildingResource;
use App\Entity\Planet;
use App\Entity\PlanetBuildings;
use App\Entity\PlayerExperience;
use App\Entity\QueueBuilding;
use App\Repository\ExperienceRepository;
use App\Repository\PlayerRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class BuildingService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PlayerRepository $playerRepository,
        private readonly ExperienceRepository $experienceRepository,
    ) {}

    public function build(Building $building)
    {
        // Change to current user
        $player = $this->playerRepository->findOneBy(['name' => 'Panpan']);

        // Change to specific planet
        $planet = $player?->getPlanets()->first();

        // Check if planet has enough resources

        $buildingToImprove = null;
        foreach ($planet->getBuildings() as $buildingOnPlanet) {
            if ($buildingOnPlanet->getBuilding() !== $building) {
                continue;
            }
            $buildingToImprove = $buildingOnPlanet;
        }

        if (null === $buildingToImprove) {
            $buildingToImprove = (new PlanetBuildings())
                ->setBuilding($building)
                ->setPlanet($planet)
                ->setLevel(1);
            $this->entityManager->persist($buildingToImprove);
            $this->entityManager->flush();
        }

        $nextLevel = $buildingToImprove->getLevel() + 1;

        $queueBuilding = (new QueueBuilding())
            ->setPlanetBuilding($buildingToImprove->setLevel($nextLevel))
            ->setStartedAt(new DateTimeImmutable())
            ->setFinishedAt(new DateTimeImmutable('tomorrow'));
        $this->entityManager->persist($queueBuilding);

        // Add production experience (when building is finished)
        $productionExperience = $this->experienceRepository->findOneBy(['name' => 'Production']);

        $quantity = 0;
        foreach ($building->getBasePrices() as $basePrice) {
            $resource = $basePrice->getResource();
            $quantity += $basePrice->getQuantity() * $nextLevel * $resource?->getCoef();
        }

        $experience = (new PlayerExperience())
            ->setPlayer($player)
            ->setQuantity($quantity)
            ->setExperience($productionExperience);
        $this->entityManager->persist($experience);

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
            return new BuildingDTO($building, $level, $this->getBuildingCost($building, $level));
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
}