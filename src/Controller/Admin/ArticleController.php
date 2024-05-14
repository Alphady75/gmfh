<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\User;
use App\Form\Admin\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/article')]
class ArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger,
        private PaginatorInterface $paginator
    ) {
    }

    #[Route('/', name: 'admin_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        $articles = $this->paginator->paginate(
            $this->articleRepository->findBy([], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('admin/article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/new', name: 'admin_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $article = $this->createIfNotExist($user);;
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setSlug($this->slugger->slug(strtolower($form->get('name')->getData())));
            $article->setOnline(true);
            $article->setComplet(true);
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            $article->setSlug($this->slugger->slug($article->getSlug() . '-' . $article->getId()));
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('admin/article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{slug}/edit', name: 'admin_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setSlug($this->slugger->slug(strtolower($form->get('name')->getData())));
            $this->entityManager->flush();

            $article->setSlug($this->slugger->slug($article->getSlug() . '-' . $article->getId()));
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($article);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('admin_article_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Create if does not exist
     *
     * @param Article $article
     * @param User $user
     * @return Article
     */
    public function createIfNotExist(User $user)
    {
        $article = $this->articleRepository->findOneBy(['user' => $user, 'complet' => 0]);

        if (!$article) {

            $article = new Article();
            $article->setUser($user);
            $article->setComplet(false);
            $article->setOnline(false);
            $this->entityManager->persist($article);
            $this->entityManager->flush();
        }

        return $article;
    }
}
