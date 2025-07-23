<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\BuildingService;
use App\Service\ResourceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class BuildingController extends AbstractController
{
    public function __construct(
        private readonly ResourceService $resourceService,
        private readonly BuildingService $buildingService,
    ) {}

    #[Route('/buildling', name: 'buildling')]
    public function __invoke(#[CurrentUser] User $user): Response
    {
        $player = $user->getOriginalPlayer();
        $activePlanet = $player->getPlanets()->first();
        $resources = $this->resourceService->getResourcesProduction($activePlanet);
        $constructions = [];
        $buildlings = $this->buildingService->getBuildingsDtoForPlanet($activePlanet);

        return $this->render('pages/building.html.twig', [
            'activePlanet' => $activePlanet,
            'resources' => $resources,
            'constructions' => $constructions,
            'buildlings' => $buildlings,
        ]);
    }
}
