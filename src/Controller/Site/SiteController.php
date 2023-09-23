<?php

namespace App\Controller\Site;

use App\Repository\ConfigurationSiteRepository;
use App\Repository\DeskRepository;
use App\Repository\LegalInformationRepository;
use App\Service\EluService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    public function __construct(
        private DeskRepository $deskRepository,
        private LegalInformationRepository $legalInformationRepository
    )
    {
    }

    #[Route('/', name: 'app_site_home')]
    public function index(): Response
    {
        return $this->render('site/pages/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    #[Route('/le-bureau-du-sapef', name: 'app_site_bureau')]
    public function bureau(): Response
    {
        return $this->render('site/pages/bureau_du_sapef.html.twig', [
            'bureaux' => $this->deskRepository->findAll()
         ]);
    }

    #[Route('/les-elus-du-sapef', name: 'app_site_elus')]
    public function elus(EluService $eluService): Response
    {
        $donnees = $eluService->constructionOfTheMapOfFranceWithElus();

        return $this->render('site/pages/elus_du_sapef.html.twig', [
            'donnees' => $donnees
        ]);
    }

    #[Route('/a-propos', name: 'app_site_about')]
    public function about(): Response
    {
        $meetingValues = $this->meetingService->nextMeetingCalc();

        return $this->render('site/pages/about.html.twig', []);
    }

    #[Route('/adherer-au-sapef', name: 'app_site_adherer')]
    public function adherer(ConfigurationSiteRepository $configurationSiteRepository): Response
    {

        return $this->render('site/pages/adherer.html.twig', [
            'configuration' => $configurationSiteRepository->findOneBy([])
        ]);
    }


    #[Route('/mentions-legales', name: 'app_site_mentions')]
    public function mentionsLegales(): Response
    {

        return $this->render('site/pages/mentions_legales.html.twig', [
            'legales' => $this->legalInformationRepository->findOneBy(['isOnline' => true], ['id' => 'ASC'])
        ]);
    }
}
