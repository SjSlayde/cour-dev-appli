<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //on crée une instance de Contact
            $message = new Contact();
            // Traitement des données du formulaire
            $data = $form->getData();
            //on stocke les données récupérées dans la variable $message
            $message = $data;

            $entityManager->persist($message);
            $entityManager->flush();


            // fonctionne pas 
            $email = $message['email'];
            $bodymessage = $message['message'];
            $Objet = $message['objet'];


            $email = (new TemplatedEmail())
            ->from('hello@example.com')
//            ->to('you@example.com')
            ->to(new Address($email))
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

        $mailer->send($email);

            // Redirection vers accueil
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('contact/index.html.twig', [
//            'form' => $form->createView(),
              'form' => $form
        ]);
    }
}
