<?php

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use App\Repository\CandidatureRepository;
use App\Repository\OffreRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/dashboard')]
class DashboardController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository,
        private OffreRepository $offreRepository,
        private CandidatureRepository $candidatureRepository,
        private UserRepository $userRepository,
        private ArticleRepository $articleRepository
    ) {
    }

    #[Route('/', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'totalpost' => count($this->postRepository->findAll()),
            'totaloffre' => count($this->offreRepository->findAll()),
            'totalcandidature' => count($this->candidatureRepository->findAll()),
            'totalarticle' => count($this->articleRepository->findAll()),
            'totaluser' => count($this->userRepository->findAll()),
            'postonline' => count($this->postRepository->findBy(['online' => true])),
            'offreonline' => count($this->offreRepository->findBy(['status' => 'Actif'])),
            'postbooster' => count($this->postRepository->findBy(['booster' => true])),
            'offrebooster' => count($this->offreRepository->findBy(['booster' => true])),
            'admins' => count($this->userRepository->findBy(['compte' => 'ADMINISTRATEUR'])),
            'particuliers' => count($this->userRepository->findBy(['compte' => 'PARTICULIER'])),
            'clients' => count($this->userRepository->findBy(['compte' => 'PERSONNEL'])),
            'articleonline' => count($this->articleRepository->findBy(['online' => true])),
        ]);
    }
}
