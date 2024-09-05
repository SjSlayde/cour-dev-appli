<?php

namespace App\Service;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    private $mailer;

    //On injecte dans le constructeur le MailerInterface

    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }

    //On crée une méthode pour envoyer un mail
    public function sendMail(string $emailpros ,string $emailclient, string $Objet, string $bodymessage){

        $email = (new TemplatedEmail())
        ->from('$emailpros')

//            ->to('you@example.com')

        ->to(new Address($emailclient))

        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)

        ->subject($Objet)

        // ->addPart((new DataPart(fopen('/assets/images/FXQErTuWIAMRakI.jpeg', 'r'), 'logo', 'image/png'))->asInline())
        // ->addPart((new DataPart(new File('/path/to/images/signature.gif'), 'footer-signature', 'image/gif'))->asInline())
    
        // utiliser la syntaxe 'cid:' + "nom de l'image intégrée " pour référencer l'image
        // ->html('<img src="cid:logo"> ... <img src="cid:footer-signature"> ...')
    
        // utiliser la même syntaxe pour les images intégrées en tant que background
        // ->html('... <div background="cid:footer-signature"> ... </div> ...')

        // le chemin de la vue Twig à utiliser dans le mail

        ->htmlTemplate('contact/signup.html.twig')

        // un tableau de variable à passer à la vue; 
       //  on choisit le nom d'une variable pour la vue et on lui attribue une valeur (comme dans la fonction `render`) :

        ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => 'foo',
                'message'=> $bodymessage,
            ]);

            $this->mailer->send($email);
    }

}