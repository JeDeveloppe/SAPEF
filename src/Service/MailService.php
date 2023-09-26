<?php

namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailService
{
    public function __construct(
        private MailerInterface $mailer
        ){
    }

    public function sendMail($recipient, $subject, $template, array $donnees){

        $mail = (new TemplatedEmail())
            ->from(new Address($_ENV['ADRESSE_EMAIL_SITE'], 'LE SAPEF'))
            ->to($recipient)
            ->subject($subject)
            ->htmlTemplate('email/'.$template.'.html.twig')
            ->context($donnees);

        try{
            $this->mailer->send($mail);
        } catch (TransportExceptionInterface $e) {
            dump($e->getDebug());
        }
    }
}