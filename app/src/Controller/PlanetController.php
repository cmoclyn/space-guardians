<?php

namespace App\Controller;

use App\Entity\Planet;
use App\Entity\Resource;
use App\Entity\User;
use App\Service\ResourceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class PlanetController extends AbstractController
{
    public function __construct(
        private readonly ResourceService $resourceService,
    ) {}

    #[Route('/planet/{planet}/resource/{resource}', name: 'planet_resource')]
    public function planetResource(Planet $planet, Resource $resource, #[CurrentUser] User $user): Response
    {
        if(!$user->getPlayers()->contains($planet->getOwner())){
            throw $this->createAccessDeniedException();
        }

        $resourceProduction = $this->resourceService->getResourceProduction($planet, $resource);

        return $this->json($resourceProduction);
    }
}
