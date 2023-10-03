<?php

namespace App\Controller\Site;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Invitation;
use App\Entity\ResetPassword;
use App\Form\EmailForSendResetPasswordType;
use App\Form\ResetPasswordType;
use App\Service\MeetingService;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ResetPasswordRepository;
use App\Service\ResetPasswordService;
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
        private UserRepository $userRepository,
        private ResetPasswordRepository $resetPasswordRepository,
        private ResetPasswordService $resetPasswordService
    )
    {
    }

    #[Route('/inscription/{uuid}', name: 'registration_register')]
    public function register(Invitation $invitation, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {

        if($invitation->getUser() != null){

            $this->addFlash('warning', 'Invitation déjà utilisée !');
            return $this->redirectToRoute('app_site_home');
        }

        $user = new User();

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

    #[Route('/check-email', name: 'registration_check_email')]
    public function checkEmail(Request $request): Response
    {

        $form = $this->createForm(EmailForSendResetPasswordType::class, null);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user = $this->userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if(!$user){

                $form->get('email')->addError(new FormError('Aucun compte n\'est associé à cette adresse email...'));

            }else{

                //TODO envoi email
                $resetPassword = new ResetPassword();
                $resetPassword->setEmail($form->get('email')->getData());
                $this->resetPasswordService->saveResetPasswordInDatabaseAndSendEmail($resetPassword);

                $this->addFlash('success', 'Mot de passe mis à jour !');
                return $this->redirectToRoute('app_site_home');
            }
        }

        return $this->render('site/registration/email_to_send_link_for_reset_password.html.twig', [
            'emailForSendResetPasswordForm' => $form->createView(),
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }

    #[Route('/reset-password/{uuid}', name: 'registration_reset_password')]
    public function resetPassword($uuid, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {

        $resetPassword = $this->resetPasswordRepository->findOneBy(['uuid' => $uuid]);

        if(!$resetPassword OR $resetPassword->isIsUsed() != false){

            $this->addFlash('warning', 'Demande inconnue ou déjà utilisée !');
            return $this->redirectToRoute('app_site_home');
        }

        $form = $this->createForm(ResetPasswordType::class, null);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if($form->get('password')->getData() !== $form->get('passwordVerify')->getData()){

                $form->get('password')->addError(new FormError('Les mots de passe ne sont pas identiques...'));

            }else{

                // encode the plain password
                $user = $this->userRepository->findOneBy(['email' => $resetPassword->getEmail()]);
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                    );

                $entityManager->persist($user);

                //update invitation
                $resetPassword->setIsUsed(true);

                $entityManager->persist($resetPassword);

                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe mis à jour !');
                return $this->redirectToRoute('app_site_home');
            }
        }

        return $this->render('site/registration/reset_password.html.twig', [
            'resetPasswordForm' => $form->createView(),
            'donneesMeeting' => $this->meetingService->nextMeetingCalc()
        ]);
    }
}
