<?php

namespace App\Service;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;


class InvitationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailService $mailService
        ){
    }

    public function saveInvitationInDatabaseAndSendEmail($invitation, $createdUser){

        $invitation->setSendAt(new DateTimeImmutable('now'))->setCreatedBy($createdUser)->setUuid(Uuid::v4());

        $this->em->persist($invitation);
        $this->em->flush();

        $donnees = [
            'recipient' => $invitation->getEmail(),
            'host' => $createdUser->getNickname(),
            'uuid' => $invitation->getUuid()
        ];

        //TODO A MONTRER EN REUNION FORMAT EMAIL
        $this->mailService->sendMail($invitation->getEmail(),'Invitation au site LE SAPEF', 'invitation_to_registration', $donnees);
    }

}