<?php

namespace App\Service;

use DateTimeImmutable;
use App\Entity\Invitation;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class InvitationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailService $mailService,
        private Security $security
        ){
    }

    public function saveInvitationInDatabaseAndSendEmail(string $recipient){

            $invitation = new Invitation();
            $invitation->setEmail($recipient);

            $createdUser = $this->security->getUser();

            $invitation->setSendAt(new DateTimeImmutable('now'))->setCreatedBy($createdUser)->setUuid(Uuid::v4());

            $this->em->persist($invitation);
            $this->em->flush();

            $donnees = [
                'recipient' => $recipient,
                'host' => $createdUser->getNickname() ?? $createdUser->getEmail(),
                'uuid' => $invitation->getUuid()
            ];

            //TODO A MONTRER EN REUNION FORMAT EMAIL
            $this->mailService->sendMail($invitation->getEmail(),'Invitation au site LE SAPEF', 'invitation_to_registration', $donnees);

    }

}