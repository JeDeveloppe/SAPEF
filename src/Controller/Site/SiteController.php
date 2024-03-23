<?php

namespace App\Controller\Site;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\EluService;
use App\Service\ContactService;
use App\Service\MeetingService;
use App\Repository\DeskRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LegalInformationRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ConfigurationSiteRepository;
use App\Repository\PostRepository;
use App\Repository\RegionErmRepository;
use App\Service\MailService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
use Knp\Component\Pager\PaginatorInterface;

class SiteController extends AbstractController
{
    public function __construct(
        private DeskRepository $deskRepository,
        private LegalInformationRepository $legalInformationRepository,
        private MeetingService $meetingService,
        private Security $security,
        private ContactService $contactService,
        private RegionErmRepository $regionErmRepository,
        private MailService $mailService,
        private PaginatorInterface $paginatorInterface
    )
    {
    }

    #[Route('/', name: 'app_site_home')]
    public function index(PostRepository $postRepository, Request $request): Response
    {
        $donnees = $postRepository->findBy(['isOnline' => true],['id' => 'DESC'], 3);

        $posts = $this->paginatorInterface->paginate(
            $donnees, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('site/pages/index.html.twig', [
            'donneesMeeting' => $this->meetingService->nextMeetingCalc(),
            'posts' => $posts
        ]);
    }

    #[Route('/les-actualites', name: 'app_site_actualities')]
    public function actualities(PostRepository $postRepository, Request $request): Response
    {
        $donnees = $postRepository->findBy(['isOnline' => true],['id' => 'DESC']);

        $posts = $this->paginatorInterface->paginate(
            $donnees, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('site/pages/posts.html.twig', [
            'donneesMeeting' => $this->meetingService->nextMeetingCalc(),
            'posts' => $posts
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
            $user = $this->security->getUser();

            if($user){
                $phone = $user->getPhone();
                $email = $user->getEmail();
            }else{
                $phone = $form->get('phone')->getData();
                $email = $form->get('email')->getData();
            };

            //envoi vers l'adresse du SAPEF
            $donnees = [
                'question' => $form->get('question')->getData(),
                'phone' => $phone
            ];
            
            $this->mailService->sendMailToSapefAdresse($email,$form->get('subject')->getData(), 'contact_question', $donnees);

            //envoi dans la BDD
            // $this->contactService->saveQuestionInDatabase($entity, $form);

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
        $regions = $this->regionErmRepository->findAll();

        return $this->render('site/pages/elus_du_sapef.html.twig', [
            'donnees' => $donnees,
            'legends' => $regions,
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
