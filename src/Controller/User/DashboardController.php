<?php

namespace App\Controller\User;

use App\Entity\Dto\Candidature;
use App\Form\Dto\CandidatureType;
use App\Repository\CandidatureRepository;
use App\Repository\OffreRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/partenaire/tableau-de-bord')]
class DashboardController extends AbstractController
{
    public function __construct(private OffreRepository $offreRepository, private PostRepository $postRepository)
    {
        
    }

    #[Route('', name: 'user_dashboard')]
    public function dashboard(CandidatureRepository $candidatureRepository, Request $request): Response
    {
        $clear = false;
        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0)
            $clear = true;

        $user = $this->getUser();
        $candidatures = $candidatureRepository->findUserCondidats($this->getUser(), 10);

        $search = new Candidature;
        $search->page = $request->get('page', 1);
        $form = $this->createForm(CandidatureType::class, $search);
        $form->handleRequest($request);
        $candidatures = $candidatureRepository->auteurFilter($search, $this->getUser());

        return $this->render('user/dashboard/index.html.twig', [
            'offres' => $this->offreRepository->findBy(['user' => $user, 'complet' => 1]),
            'posts' => $this->postRepository->findBy(['user' => $user]),

            'totalcandidatures' => $candidatures,
            'form' => $form->createView(),
            'candidatures' => $candidatures,
            'clear' => $clear,
            'path' => 'user_dashboard',
        ]);
    }
}
