<?php

namespace App\Controller\User;

use App\Form\User\ProfilEntrepriseType;
use App\Form\User\ProfilParticulierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/espace-membre')]
class EspaceMembreController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }

    #[Route('/', name: 'user_espace')]
    public function index(): Response
    {
        return $this->render('user/espace/index.html.twig', [
            'controller_name' => 'EspaceMembreController',
        ]);
    }

    #[Route('/completer-mon-compte', name: 'user_complete_compte')]
    public function completeCompte(Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();
        $formType = ProfilParticulierType::class;

        if ($user->getCompte() == 'ENTREPRISE') {
            $formType = ProfilEntrepriseType::class;
        }

        $form = $this->createForm($formType, $user, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
        }

        return $this->render('user/espace/complete_compte.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
