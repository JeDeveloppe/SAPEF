<?php

namespace App\Controller\Site;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\EluService;
use App\Service\MailService;
use App\Service\ContactService;
use App\Service\MeetingService;
use App\Repository\DeskRepository;
use App\Repository\PostRepository;
use App\Repository\RegionErmRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LegalInformationRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ConfigurationSiteRepository;
use App\Repository\MeetingRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;

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
        private PaginatorInterface $paginatorInterface,
        private MeetingRepository $meetingRepository
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
        $now = new DateTimeImmutable('now');

        return $this->render('site/pages/bureau_du_sapef.html.twig', [
            'bureaux' => $this->deskRepository->findBy([], ['orderOfAppearance' => 'ASC']),
            'donneesMeeting' => $this->meetingService->nextMeetingCalc(),
            'legales' => $this->legalInformationRepository->findOneBy(['isOnline' => true], ['id' => 'ASC']),
            'nextPermanances' => $this->meetingRepository->findAllNextMeetingAfterThisDate($now)
        ]);
    }

    #[Route('/les-elus-du-sapef', name: 'app_site_elus')]
    public function elus(EluService $eluService): Response
    {
        $donnees = $eluService->constructionOfTheMapOfFranceWithElus();
        $regions = $this->regionErmRepository->findAll();
        $now = new DateTimeImmutable('now');

        return $this->render('site/pages/elus_du_sapef.html.twig', [
            'donnees' => $donnees,
            'legends' => $regions,
            'donneesMeeting' => $this->meetingService->nextMeetingCalc(),
            'legales' => $this->legalInformationRepository->findOneBy(['isOnline' => true], ['id' => 'ASC']),
            'nextPermanances' => $this->meetingRepository->findAllNextMeetingAfterThisDate($now)
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
            'legales' => $this->legalInformationRepository->findOneBy(['isOnline' => true], ['id' => 'ASC']),
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

    #[Route('/download/adhesion-{year}', name: 'app_download_adhesion')]
    public function download($year)
    {
        // load the file from the filesystem
        $file = new File('../public/download/adhesion_'.$year.'.pdf');
        if(!$file){

            $this->addFlash('warning','Année du document non connue !!!');

            return $this->redirectToRoute('app_site_home');

        }else{

            return $this->file($file);

            // // rename the downloaded file
            // return $this->file($file, 'custom_name.pdf');

            // display the file contents in the browser instead of downloading it
            // return $this->file('adhesion_'.$year.'.docx', 'my_invoice.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
        }
    }
}
