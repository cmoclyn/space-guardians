<?php

namespace App\Service\Generator;

use App\Entity\Galaxy;
use App\Entity\Planet;
use App\Entity\SolarSystem;
use App\Event\SolarSystemCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SolarSystemGenerator
{
    private const MIN_PLANET_DISTANCE = 3; // Distance minimale entre les planètes d'un même système
    private const MIN_SYSTEM_PLANET_DISTANCE = 7; // Distance minimale entre les planètes de systèmes différents

    private $allPlanets = []; // Stockage des positions des planètes de tous les systèmes

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @throws RandomException
     */
    public function generateSolarSystem(SolarSystem $solarSystem): void
    {
        $distanceToCenter = sqrt(($solarSystem->getPositionX() ** 2) + ($solarSystem->getPositionY() ** 2));
        $maxRadius = GalaxyGenerator::MAX_GALAXY_RADIUS;
        $maxPlanetsProbability = 1 - ($distanceToCenter / $maxRadius);

        $planetCount = random_int(5, 12);
        if (random_int(0, 100) / 100 < $maxPlanetsProbability) {
            $planetCount = 12;
        }

        $planets = [];
        for ($i = 0; $i < $planetCount; $i++) {
            $planetPosition = $this->generateValidPlanetPosition($planets, $solarSystem);

            $planet = new Planet();
            $planet->setSolarSystem($solarSystem);
            $planet->setName($solarSystem->getName() . ' - Planet ' . ($i + 1));
            $planet->setPositionX($planetPosition['x']);
            $planet->setPositionY($planetPosition['y']);

            $this->entityManager->persist($planet);

            // Enregistrer les positions
            $planets[] = $planetPosition;
            $this->allPlanets[] = $planetPosition;
        }

        $this->entityManager->flush();
    }

    /**
     * @throws RandomException
     */
    private function generateValidPlanetPosition(array $planetsInSystem, SolarSystem $solarSystem): array
    {
        $isValid = false;
        $position = [];

        while (!$isValid) {
            $x = random_int(-100, 100); // Distance aléatoire par rapport au centre du système
            $y = random_int(-100, 100);

            $position = [
                'x' => $solarSystem->getPositionX() + $x,
                'y' => $solarSystem->getPositionY() + $y,
            ];

            if ($this->isPlanetPositionValid($position, $planetsInSystem)) {
                $isValid = true;
            }
        }

        return $position;
    }

    private function isPlanetPositionValid(array $newPlanetPosition, array $planetsInSystem): bool
    {
        // Vérifier les distances minimales avec les planètes du même système
        foreach ($planetsInSystem as $existingPlanet) {
            $distance = sqrt(
                (($existingPlanet['x'] - $newPlanetPosition['x']) ** 2) + (($existingPlanet['y'] - $newPlanetPosition['y']) ** 2)
            );
            if ($distance < self::MIN_PLANET_DISTANCE) {
                return false;
            }
        }

        // Vérifier les distances minimales avec les planètes des autres systèmes
        foreach ($this->allPlanets as $existingPlanet) {
            $distance = sqrt(
                (($existingPlanet['x'] - $newPlanetPosition['x']) ** 2) + (($existingPlanet['y'] - $newPlanetPosition['y']) ** 2)
            );
            if ($distance < self::MIN_SYSTEM_PLANET_DISTANCE) {
                return false;
            }
        }

        return true;
    }

}