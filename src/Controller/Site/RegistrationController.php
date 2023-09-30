<?php

namespace App\Controller\Site;

use App\Entity\Invitation;
use App\Entity\User;
use DateTimeImmutable;
use App\Service\MeetingService;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private MeetingService $meetingService,
    )
    {
    }

    #[Route('/inscription/{uuid}', name: 'site_register')]
    public function register(Invitation $invitation, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {

        //TODO mettre a jour en fonction de la manière voulu pour inscription
        if($invitation->getUser() != null){

            $this->addFlash('warning', 'Invitation déjà utilisée !');
            return $this->redirectToRoute('app_site_home');
        }
        //TODO mettre a jour en fonction de la manière voulu pour inscription


        $user = new User();

        //TODO mettre a jour en fonction de la manière voulu pour inscription
        $user->setEmail($invitation->getEmail());

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $form->get('password')->getData() == $form->get('passwordVerify')->getData()) {
            // encode the plain password
            $now = new DateTimeImmutable('now');
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            )
            ->setEmail($form->get('email')->getData())
            ->setLastname(strtoupper($form->get('lastname')->getData()))
            ->setFirstname(ucfirst($form->get('firstname')->getData()))
            ->setCreatedAt($now)
            ->setLastVisiteAt($now);

            $entityManager->persist($user);

            //update invitation
            $invitation->setUser($user);

            //TODO mettre a jour en fonction de la manière voulu pour inscription
            $entityManager->persist($invitation);

            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('site/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }
}
