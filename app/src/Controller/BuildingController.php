<?php

namespace App\Controller;

use App\Entity\Building;
use App\Entity\Planet;
use App\Entity\User;
use App\Service\BuildingService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class BuildingController extends AbstractController
{
    public function __construct(
        private readonly BuildingService $buildingService,
    ) {}

    #[Route('/buildling', name: 'buildling')]
    public function __invoke(#[CurrentUser] User $user): Response
    {
        $player = $user->getOriginalPlayer();
        $activePlanet = $player->getPlanets()->first();
//        $resources = $this->resourceService->getResourcesProduction($activePlanet);
        $buildlings = $this->buildingService->getBuildingsDtoForPlanet($activePlanet);

        return $this->render('pages/building.html.twig', [
            'buildlings' => $buildlings,
        ]);
    }

    #[Route('/planet/{planet}/build/{building}', name: 'building_queue')]
    public function build(Planet $planet, Building $building, #[CurrentUser] User $user): Response
    {
        try {
            if (!$user->getPlayers()->contains($planet->getOwner())) {
                throw $this->createAccessDeniedException();
            }

            $this->buildingService->build($planet, $building);
            $this->addFlash('success', sprintf('Construction de %s lancée !', $building->getName()));
            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }
    }
}
