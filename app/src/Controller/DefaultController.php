<?php

namespace App\Controller;

use App\Controller\Admin\BuildingQueueCrudController;
use App\Entity\User;
use App\Repository\BuildingRepository;
use App\Service\BuildingService;
use App\Service\ResourceService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class DefaultController extends AbstractController
{
    public function __construct(
        private readonly ResourceService $resourceService,
    ) {}

    #[Route('/', name: 'index')]
    public function __invoke(#[CurrentUser] User $user): Response
    {
        $player = $user->getOriginalPlayer();
        $activePlanet = $player->getPlanets()->first();
        $resources = $this->resourceService->getResourcesProduction($activePlanet);
        $missions = [];
        $constructions = [];
        $reports = [];
        $events = [];
        $topPlayers = [];
        $playerRank = 1;
        return $this->render('dashboard.html.twig', [
            'player' => $player,
            'activePlanet' => $activePlanet,
            'resources' => $resources,
            'missions' => $missions,
            'constructions' => $constructions,
            'reports' => $reports,
            'galacticEvents' => $events,
            'topPlayers' => $topPlayers,
            'playerRank' => $playerRank,
        ]);
    }
}
