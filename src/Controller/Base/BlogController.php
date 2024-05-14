<?php

namespace App\Controller\Base;

use App\Entity\Article;
use App\Entity\ArticleCategorie;
use App\Entity\Comment;
use App\Entity\Tag;
use App\Form\Base\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        private PaginatorInterface $paginator,
        private EntityManagerInterface $manager,
        private CommentRepository $commentRepository
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
            'categorie' => null,
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

        return $this->render('blog/categories.html.twig', [
            'articles' => $articles,
            'categorie' => $categorie,
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

        return $this->render('blog/tags.html.twig', [
            'articles' => $articles,
            'tag' => $tag,
        ]);
    }

    #[Route('/{slug}', name: 'blog_details', methods: ['GET', 'POST'])]
    public function details(Article $article, Request $request): Response
    {
        $similars = $this->articleRepository->findBy(['categorie' => $article->getCategorie()], ['created' => 'DESC']);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # save comment
            $comment->setArticle($article);
            if ($this->getUser())
                $comment->setUser($this->getUser());
            $this->manager->persist($comment);
            $this->manager->flush();
            return $this->redirectToRoute('blog_details', ['slug' => $article->getSlug()]);
        }

        return $this->render('blog/details.html.twig', [
            'article' => $article,
            'categorie' => $article->getCategorie(),
            'similars' => $similars,
            'comments' => $this->commentRepository->findBy(['article' => $article], ['created' => 'DESC']),
            'form' => $form->createView(),
        ]);
    }
}
