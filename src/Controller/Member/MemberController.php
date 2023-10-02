<?php

namespace App\Controller\Member;

use App\Entity\User;
use App\Form\AccountType;
use App\Repository\ContactRepository;
use App\Repository\PaiementRepository;
use App\Repository\UserRepository;
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
        private UserRepository $userRepository
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

            // $userInDataBase = $this->userRepository->find($user);
            // $passwordInDataBaseEncoded = $userPasswordHasher->hashPassword(
            //                 $userInDataBase,
            //                 $userInDataBase->getPassword()
            // );

            // $passwordInFormEncoded = $userPasswordHasher->hashPassword(
            //                             $userInDataBase,
            //                             $form->get('password')->getData()
            // );

            // if($passwordInDataBaseEncoded != $passwordInDataBaseEncoded){

            //     $user->setPassword($userPasswordHasher->hashPassword(
            //         $user,
            //         $form->get('password')->getData()
            //     ));
            // }

            // dd($user);
            $this->entityManagerInterface->persist($user);
            $this->entityManagerInterface->flush();

            $this->addFlash('success', 'Données mises à jour !');
        }

        return $this->render('member/account.html.twig', [
            'accountForm' => $form->createView(),
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }
}
