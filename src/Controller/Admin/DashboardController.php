<?php

namespace App\Controller\Admin;

use App\Entity\Shop;
use App\Entity\User;
use App\Entity\RegionErm;
use App\Entity\Department;
use App\Entity\Desk;
use App\Entity\DeskRole;
use App\Entity\Job;
use App\Entity\MeanOfPaiement;
use App\Entity\Meeting;
use App\Entity\MeetingName;
use App\Entity\MeetingPlace;
use App\Entity\Paiement;
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
        
        yield MenuItem::section('Gestion des réunions:');
        yield MenuItem::linkToCrud('Les réunions', 'fas fa-list', Meeting::class);
        yield MenuItem::linkToCrud('Liste des types de réunions', 'fas fa-list', MeetingName::class);
        yield MenuItem::linkToCrud('Liste des lieux', 'fas fa-list', MeetingPlace::class);

        yield MenuItem::section('Gestion du bureau:');
        yield MenuItem::linkToCrud('Le bureau', 'fas fa-list', Desk::class);
        yield MenuItem::linkToCrud('Liste des roles', 'fas fa-list', DeskRole::class);

        yield MenuItem::section('Gestion des centres:');
        yield MenuItem::linkToCrud('Centres', 'fas fa-list', Shop::class);

        yield MenuItem::section('Gestion des paiements:');
        yield MenuItem::linkToCrud('Liste des paiements', 'fas fa-list', Paiement::class);
        yield MenuItem::linkToCrud('Liste des moyens de paiement', 'fas fa-list', MeanOfPaiement::class);


        yield MenuItem::section('Gestion des membres:');
        yield MenuItem::linkToCrud('Liste des membres', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Liste des métiers', 'fas fa-list', Job::class);

        yield MenuItem::section('Paramètres géographiques:');
        yield MenuItem::linkToCrud('Régions ERM', 'fas fa-list', RegionErm::class);
        yield MenuItem::linkToCrud('Départements', 'fas fa-list', Department::class);
    }
}
