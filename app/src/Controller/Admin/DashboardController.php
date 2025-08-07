<?php

namespace App\Controller\Admin;

use App\Entity\Building;
use App\Entity\BuildingResource;
use App\Entity\BuildingType;
use App\Entity\Galaxy;
use App\Entity\Planet;
use App\Entity\Player;
use App\Entity\QueueBuilding;
use App\Entity\Resource;
use App\Entity\SolarSystem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
//        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(PlayerCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Space Guardians');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // Section utilisateurs
        yield MenuItem::section('User management');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', Player::class);

        // Section galaxies, systèmes solaires et planètes
        yield MenuItem::section('Universe');
        yield MenuItem::linkToCrud('Planets', 'fa fa-globe', Planet::class);
        yield MenuItem::linkToCrud('Solar Systems', 'fa fa-sun', SolarSystem::class);
        yield MenuItem::linkToCrud('Galaxies', 'fa fa-atom', Galaxy::class);

        // Section bâtiments
        yield MenuItem::section('Buildings');
        yield MenuItem::linkToCrud('Building types', 'fa fa-industry', BuildingType::class);
        yield MenuItem::linkToCrud('Buildings', 'fa fa-industry', Building::class);

        // Section bâtiments
        yield MenuItem::section('Settings');
        yield MenuItem::linkToCrud('Resources', 'fa fa-gem', Resource::class);

        // Section actions
        yield MenuItem::section('Actions');
        yield MenuItem::linkToRoute('Build a building', 'fa fa-industry', 'index');

        // Section files d'attente
        yield MenuItem::section('Queues');
        yield MenuItem::linkToCrud('Building queue', 'fa fa-industry', QueueBuilding::class);

    }
}
