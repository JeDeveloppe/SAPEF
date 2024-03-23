<?php

namespace App\Service;

use phpDocumentor\Reflection\Types\Null_;
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

    public function sendMail($recipient, $subject, $template, array $donnees = null){

        if(is_null($donnees)){
            $donnees = [];
        }

        $mail = (new TemplatedEmail())
            ->from(new Address($_ENV['ADRESSE_EMAIL_SITE'], $_ENV['FROM_EMAIL_NAME']))
            ->to($recipient)
            ->replyTo('no_reply@lesapef.fr')
            ->subject($subject)
            ->htmlTemplate('email/templates/'.$template.'.html.twig')
            ->context($donnees);

        try{
            $this->mailer->send($mail);
        } catch (TransportExceptionInterface $e) {
            dump($e->getDebug());
        }
    }

    public function sendMailToSapefAdresse($recipient, $subject, $template, array $donnees = null){

        if(is_null($donnees)){
            $donnees = [];
        }

        $mail = (new TemplatedEmail())
            ->from($_ENV['ADRESSE_EMAIL_SITE'])
            ->to($_ENV['ADRESSE_EMAIL_SAPEF'])
            ->replyTo($recipient)
            ->subject($subject)
            ->htmlTemplate('email/templates/'.$template.'.html.twig')
            ->context($donnees);

        try
        {
            $mail->getHeaders()->addTextHeader('X-Transport', 'main');
            $this->mailer->send($mail);
        } 
        catch(TransportExceptionInterface $e)
        {
            echo "Message non envoyer :".$e->getDebug();
            dd('non envoyé');
        }
    }
}