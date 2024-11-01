<?php

namespace App\Service\Generator;

use App\Entity\Galaxy;
use App\Entity\SolarSystem;
use App\Event\SolarSystemCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class GalaxyGenerator
{
    // Les constantes pour gérer les distances minimales et maximales
    private const MIN_SYSTEM_DISTANCE = 25;
    public const MAX_GALAXY_RADIUS = 1000;

    private array $systems = []; // Liste pour stocker les positions des systèmes solaires

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $eventDispatcher
    )
    {
    }

    /**
     * @throws RandomException
     */
    public function generateGalaxy(Galaxy $galaxy, int $systemCount): void
    {
        $branchCount = 4; // Nombre de branches de la galaxie
        $branchSpread = 100; // Dispersion autour des branches

        for ($i = 0; $i < $systemCount; $i++) {
            $position = $this->generateValidSolarSystemPosition($branchCount, $branchSpread);

            $solarSystem = new SolarSystem();
            $solarSystem->setGalaxy($galaxy);
            $solarSystem->setPositionX($position['x']);
            $solarSystem->setPositionY($position['y']);
            $solarSystem->setName('System ' . ($i + 1));

            // Enregistrer la position pour vérifier les distances dans les prochaines itérations
            $this->systems[] = $position;

            $this->em->persist($solarSystem);

            $solarSystemEvent = new SolarSystemCreatedEvent($solarSystem);
            $this->eventDispatcher->dispatch($solarSystemEvent);
        }

        // Enregistrer tous les systèmes solaires
        $this->em->flush();
    }

    /**
     * @throws RandomException
     */
    private function generateValidSolarSystemPosition(int $branchCount, int $branchSpread): array
    {
        $isValid = false;
        $position = [];

        while (!$isValid) {
            // Génération de la position en spirale
            $theta = random_int(0, 4 * M_PI * 1000) / 1000;
            $radiusFactor = random_int(0, self::MAX_GALAXY_RADIUS * 1000) / 1000;
            $radius = $radiusFactor * $radiusFactor; // Créer une distribution plus dense au centre

            $branch = random_int(0, $branchCount - 1);
            $x = $radius * cos($theta + $branch * (2 * M_PI / $branchCount));
            $y = $radius * sin($theta + $branch * (2 * M_PI / $branchCount));

            // Ajouter la dispersion autour des branches
            $x += random_int(-$branchSpread, $branchSpread);
            $y += random_int(-$branchSpread, $branchSpread);

            $position = ['x' => $x, 'y' => $y];

            // Vérifier la distance avec les autres systèmes solaires
            if ($this->isPositionValid($position)) {
                $isValid = true;
            }
        }

        return $position;
    }

    private function isPositionValid(array $newPosition): bool
    {
        foreach ($this->systems as $existingSystem) {
            $distance = sqrt(
                (($existingSystem['x'] - $newPosition['x']) ** 2) + (($existingSystem['y'] - $newPosition['y']) ** 2)
            );

            // Vérifier si la distance respecte la distance minimale
            if ($distance < self::MIN_SYSTEM_DISTANCE) {
                return false;
            }
        }
        return true;
    }
}