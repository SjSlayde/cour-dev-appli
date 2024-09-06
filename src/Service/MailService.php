<?php

namespace App\Service;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private $mailer;

    //On injecte dans le constructeur le MailerInterface

    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }

    //On crée une méthode pour envoyer un mail
    public function sendMail(string $emailpros ,string $emailclient, string $Objet, string $bodymessage){

        $email = (new Email())
        ->from($emailpros)

        ->to($emailclient)
        ->subject($Objet)
        ->text($bodymessage);

            $this->mailer->send($email);
    }
}