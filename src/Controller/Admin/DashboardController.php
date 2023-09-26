<?php

namespace App\Controller\Admin;

use App\Entity\ConfigurationSite;
use App\Entity\Contact;
use App\Entity\Shop;
use App\Entity\User;
use App\Entity\RegionErm;
use App\Entity\Department;
use App\Entity\Desk;
use App\Entity\DeskRole;
use App\Entity\Elu;
use App\Entity\EluStatus;
use App\Entity\Job;
use App\Entity\LegalInformation;
use App\Entity\MeanOfPaiement;
use App\Entity\Meeting;
use App\Entity\MeetingName;
use App\Entity\MeetingPlace;
use App\Entity\Paiement;
use App\Entity\SexStatus;
use App\Repository\ContactRepository;
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
        yield MenuItem::linkToRoute('SITE','fa-solid fa-earth-europe','app_site_home');
        
        yield MenuItem::section('Gestion des questions:');
        yield MenuItem::linkToCrud('Les questions', 'fa-solid fa-circle-question', Contact::class);

        yield MenuItem::section('Gestion des réunions:');
        yield MenuItem::linkToCrud('Les réunions', 'fa-solid fa-handshake', Meeting::class);
        yield MenuItem::linkToCrud('Liste des types de réunions', 'fas fa-list', MeetingName::class);
        yield MenuItem::linkToCrud('Liste des lieux', 'fas fa-list', MeetingPlace::class);

        yield MenuItem::section('Gestion des paiements:');
        yield MenuItem::linkToCrud('Liste des paiements', 'fa-solid fa-money-bill', Paiement::class);
        yield MenuItem::linkToCrud('Liste des moyens de paiement', 'fas fa-list', MeanOfPaiement::class);

        yield MenuItem::section('Gestion des membres:');
        yield MenuItem::linkToCrud('Liste des membres', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Liste des métiers', 'fas fa-list', Job::class);
        yield MenuItem::linkToCrud('Liste des genres', 'fas fa-list', SexStatus::class);
        
        yield MenuItem::section('Gestion du bureau:');
        yield MenuItem::linkToCrud('Le bureau', 'fa-solid fa-users-rectangle', Desk::class);
        yield MenuItem::linkToCrud('Liste des roles', 'fas fa-list', DeskRole::class);

        yield MenuItem::section('Gestion des élus:');
        yield MenuItem::linkToCrud('Les élus', 'fa-solid fa-user-secret', Elu::class);
        yield MenuItem::linkToCrud('Liste des status', 'fas fa-list', EluStatus::class);

        yield MenuItem::section('Paramètres géographiques:');
        yield MenuItem::linkToCrud('Régions ERM', 'fas fa-list', RegionErm::class);
        yield MenuItem::linkToCrud('Départements', 'fas fa-list', Department::class);

        yield MenuItem::section('Gestion des centres:');
        yield MenuItem::linkToCrud('Centres', 'fas fa-list', Shop::class);

        yield MenuItem::section('Gestion administratif:');
        yield MenuItem::linkToCrud('Configurations', 'fa-solid fa-gear', ConfigurationSite::class);
        yield MenuItem::linkToCrud('Informations légales', 'fa-solid fa-scale-balanced', LegalInformation::class);
    }

}
