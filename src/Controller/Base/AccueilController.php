<?php

namespace App\Controller\Base;

use App\Entity\Dto\Offre;
use App\Form\Dto\VisiteOffreType;
use App\Repository\ArticleRepository;
use App\Repository\OffreRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository,
        private ArticleRepository $articleRepository,
        private OffreRepository $offreRepository
    ) {
    }

    #[Route('/', name: 'accueil')]
    public function index(Request $request): Response
    {
        $search = new Offre();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(VisiteOffreType::class, $search, [
            'action' => $this->generateUrl('offres'),
            'method' => 'GET',
        ]);
    
        return $this->render('accueil/index.html.twig', [
            'posts' => $this->postRepository->findBy(['online' => true], ['created' => 'DESC'], 10),
            'articles' => $this->articleRepository->findBy(['online' => true], ['created' => 'DESC'], 10),
            'offres' => $this->offreRepository->findBy(['status' => "Actif"], ['created' => 'DESC'], 6),
            "form" => $form->createView()
        ]);
    }
}
