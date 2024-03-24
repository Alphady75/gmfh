<?php

namespace App\Controller\Base;

use App\Entity\Dto\Post;
use App\Form\Dto\VisitePostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annonces')]
class PostController extends AbstractController
{
    public function __construct(private PostRepository $postRepository)
    {
        
    }

    #[Route('/', name: 'posts')]
    public function index(Request $request): Response
    {
        $search = new Post();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(VisitePostType::class, $search);
        $form->handleRequest($request);
        $posts = $this->postRepository->visiteurFilter($search, $this->getUser());

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/categorie/{slug}', name: 'categorie_posts')]
    public function categories(): Response
    {
        return $this->render('post/categories.html.twig', [
            'posts' => $this->postRepository->findBy(['created' => 'DESC']),
        ]);
    }

    #[Route('/details/{slug}', name: 'post_details')]
    public function details(): Response
    {
        return $this->render('post/details.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
}
