<?php

namespace App\Controller\Member;

use App\Entity\Invitation;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\EmailForSendInvitationType;
use App\Repository\ContactRepository;
use App\Repository\InvitationRepository;
use App\Repository\PaiementRepository;
use App\Repository\UserRepository;
use App\Service\InvitationService;
use App\Service\MeetingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/member', name: 'member_')]
class MemberController extends AbstractController
{
    public function __construct(
        private Security $security,
        private PaiementRepository $paiementRepository,
        private ContactRepository $contactRepository,
        private MeetingService $meetingService,
        private EntityManagerInterface $entityManagerInterface,
        private UserRepository $userRepository,
        private InvitationService $invitationService,
        private InvitationRepository $invitationRepository
    )
    {
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {

        $user = $this->security->getUser();

        return $this->render('member/dashboard.html.twig', [
            'paiements' => $this->paiementRepository->findBy(['name' => $user]),
            'questions' => $this->contactRepository->findBy(['user' => $user], ['id' => 'DESC'], 5),
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }

    #[Route('/mon-compte', name: 'account')]
    public function account(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->security->getUser();

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->entityManagerInterface->persist($user);
            $this->entityManagerInterface->flush();

            $this->addFlash('success', 'Données mises à jour !');
        }

        return $this->render('member/account.html.twig', [
            'accountForm' => $form->createView(),
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }

    #[Route('/invitation', name: 'invitation')]
    public function invitation(Request $request): Response
    {

        $user = $this->security->getUser();
        $form = $this->createForm(EmailForSendInvitationType::class, null);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $recipient = $form->get('email')->getData();
            $agreeTerm = $form->get('isAgreeTerms')->getData();

            $this->invitationService->saveInvitationInDatabaseAndSendEmail($recipient, $agreeTerm);

            $this->addFlash('success', 'Votre invitation à été envoyée!');
            return $this->redirectToRoute('member_invitation');
        }

        return $this->render('member/email_to_send_link_for_invitation.html.twig', [
            'emailForSendInvitationForm' => $form->createView(),
            'donneesMeeting' => $this->meetingService->nextMeetingCalc(),
            'invitations' => $this->invitationRepository->findBy(['createdBy' => $user])
        ]);
    }
}
