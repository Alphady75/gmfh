<?php

namespace App\Controller\Base;

use App\Entity\Article;
use App\Entity\ArticleCategorie;
use App\Entity\Tag;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private PaginatorInterface $paginator
    ) {
    }

    #[Route('/', name: 'blog')]
    public function blog(Request $request): Response
    {
        $articles = $this->paginator->paginate(
            $this->articleRepository->findBy(['online' => true], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/categories/{slug}', name: 'blog_categories')]
    public function categories(ArticleCategorie $categorie, Request $request): Response
    {
        $articles = $this->paginator->paginate(
            $this->articleRepository->findBy([
                'online' => true, 'categorie' => $categorie
            ], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/tags/{slug}', name: 'blog_tags')]
    public function tags(Tag $tag, Request $request): Response
    {
        $articles = $this->paginator->paginate(
            $this->articleRepository->findBy([
                'online' => true, 'tag' => $tag
            ], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/{slug}', name: 'blog_details')]
    public function details(Article $article): Response
    {
        $similars = $this->articleRepository->findBy(['categorie' => $article->getCategorie()], ['created' => 'DESC']);

        return $this->render('blog/details.html.twig', [
            'article' => $article,
            'similars' => $similars,
        ]);
    }
}
