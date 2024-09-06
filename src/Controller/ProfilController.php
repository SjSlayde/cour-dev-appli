<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;

class ProfilController extends AbstractController
{
    private $userRepo;

    public function __construct(UtilisateurRepository $userRepo){
        $this->userRepo = $userRepo;
    }
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user) {
            $identifiant = $user->getUserIdentifier();
            if($identifiant){
                $info = $this->userRepo->findOneBy(["email" =>$identifiant]);
            }
        }else {
            // Gérer le cas où aucun utilisateur n'est connecté
            throw new \LogicException('Aucun utilisateur connecté.');
        }

        return $this->render('profil/index.html.twig', [
            'informations' => $info
        ]);
    }
}
