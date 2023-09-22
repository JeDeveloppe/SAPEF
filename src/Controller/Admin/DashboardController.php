<?php

namespace App\Controller\Admin;

use App\Entity\Shop;
use App\Entity\User;
use App\Entity\RegionErm;
use App\Entity\Department;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('LE SAPEF');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('SITE','fa-solid fa-shop','app_home');
        
        yield MenuItem::section('Gestion des centres:');
        yield MenuItem::linkToCrud('Centres', 'fas fa-list', Shop::class);

        yield MenuItem::section('Gestion des paiements:');

        yield MenuItem::section('Gestion des membres:');
        yield MenuItem::linkToCrud('Membres', 'fas fa-list', User::class);

        yield MenuItem::section('Paramètres géographiques:');
        yield MenuItem::linkToCrud('Régions ERM', 'fas fa-list', RegionErm::class);
        yield MenuItem::linkToCrud('Départements', 'fas fa-list', Department::class);
    }
}
