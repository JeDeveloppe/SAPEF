<?php

namespace App\Controller\Site;

use DateTimeImmutable;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\EluService;
use App\Service\ContactService;
use App\Service\MeetingService;
use App\Repository\DeskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LegalInformationRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ConfigurationSiteRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;

class SiteController extends AbstractController
{
    public function __construct(
        private DeskRepository $deskRepository,
        private LegalInformationRepository $legalInformationRepository,
        private MeetingService $meetingService,
        private Security $security,
        private ContactService $contactService
    )
    {
    }

    #[Route('/', name: 'app_site_home')]
    public function index(): Response
    {
        return $this->render('site/pages/index.html.twig', [
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }

    #[Route('/contactez-le-sapef', name: 'app_site_contact')]
    public function contact(Request $request,  Recaptcha3Validator $recaptcha3Validator): Response
    {
        $entity = new Contact();
        $form = $this->createForm(ContactType::class, $entity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // $score = $recaptcha3Validator->getLastResponse()->getScore();
            // dump($score);

            $this->contactService->saveQuestionInDatabase($entity, $form);

            $this->addFlash('success', 'Message bien reçu - Merci');
            return $this->redirectToRoute('app_site_home');
        }

        return $this->render('site/pages/contact.html.twig', [
            'contactForm' => $form->createView(),
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }

    #[Route('/le-bureau-du-sapef', name: 'app_site_bureau')]
    public function bureau(): Response
    {
        return $this->render('site/pages/bureau_du_sapef.html.twig', [
            'bureaux' => $this->deskRepository->findBy([], ['orderOfAppearance' => 'ASC']),
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }

    #[Route('/les-elus-du-sapef', name: 'app_site_elus')]
    public function elus(EluService $eluService): Response
    {
        $donnees = $eluService->constructionOfTheMapOfFranceWithElus();

        return $this->render('site/pages/elus_du_sapef.html.twig', [
            'donnees' => $donnees,
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }

    #[Route('/a-propos', name: 'app_site_about')]
    public function about(): Response
    {

        return $this->render('site/pages/about.html.twig', [
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }

    #[Route('/adherer-au-sapef', name: 'app_site_adherer')]
    public function adherer(ConfigurationSiteRepository $configurationSiteRepository): Response
    {

        return $this->render('site/pages/adherer.html.twig', [
            'configuration' => $configurationSiteRepository->findOneBy([]),
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }


    #[Route('/mentions-legales', name: 'app_site_mentions')]
    public function mentionsLegales(): Response
    {

        return $this->render('site/pages/mentions_legales.html.twig', [
            'legales' => $this->legalInformationRepository->findOneBy(['isOnline' => true], ['id' => 'ASC']),
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }
}
