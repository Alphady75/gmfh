<?php

namespace App\Controller\User;

use App\Entity\Dto\Post as DtoPost;
use App\Entity\Post;
use App\Form\Dto\PostType as DtoPostType;
use App\Form\User\PostAbonnerType;
use App\Form\User\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/partenaire/annonces')]
class PostController extends AbstractController
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    #[Route('/', name: 'user_posts', methods: ['GET'])]
    public function index(PostRepository $postRepository, Request $request): Response
    {
        $clear = false;
        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0)
            $clear = true;

        /** @var User */
        $user = $this->getUser();

        $search = new DtoPost();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(DtoPostType::class, $search);
        $form->handleRequest($request);
        $posts = $postRepository->auteurFilter($search, $this->getUser());
        if ($user->getCompte() == 'ADMINISTRATEUR') {
            $posts = $postRepository->adminFilter($search);
        }

        return $this->render('user/post/index.html.twig', [
            'posts' => $posts,
            'clear' => $clear,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/publier-une-annonce', name: 'user_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User */
        $user = $this->getUser();

        $formType = PostType::class;
        if ($user->getAbonnement())
            $formType = PostAbonnerType::class;

        $post = new Post();
        $form = $this->createForm($formType, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setSlug(strtolower($this->slugger->slug($form->get('name')->getData())));
            $post->setUser($this->getUser());
            $post->setBloquer(false);
            $post->setBoosted(false);
            $post->setOnline(false);
            $entityManager->persist($post);
            $entityManager->flush();

            $post->setSlug($post->getSlug() . '-' . $post->getId());
            $entityManager->flush();

            $this->addFlash('success', "Le contenu a bien été enregistrer");

            return $this->redirectToRoute('user_post_show', ['slug' => $post->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}', name: 'user_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('user/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{slug}/edit', name: 'user_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setSlug(strtolower($this->slugger->slug($form->get('name')->getData())));
            $entityManager->persist($post);
            $entityManager->flush();

            $post->setSlug($post->getSlug() . '-' . $post->getId());
            $entityManager->flush();

            $this->addFlash('success', "Le contenu a bien été mis à jour");
            return $this->redirectToRoute('user_post_show', ['slug' => $post->getSlug()],  Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'user_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_posts', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/desactive/{id}', name: 'user_post_desactive', methods: ['POST'])]
    public function desactive(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('desactive' . $post->getId(), $request->request->get('_token'))) {
            $post->setOnline(0);
            $entityManager->flush();
            $this->addFlash('success', "le service a bien été masquer");
        }

        return $this->redirectToRoute('user_post_show', ['slug' => $post->getSlug()],  Response::HTTP_SEE_OTHER);
    }

    #[Route('/publier/{id}', name: 'user_post_publier', methods: ['POST'])]
    public function publier(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('publier' . $post->getId(), $request->request->get('_token'))) {
            $post->setOnline(1);
            $entityManager->flush();
            $this->addFlash('success', "le service a bien été publier");
        }

        return $this->redirectToRoute('user_post_show', ['slug' => $post->getSlug()],  Response::HTTP_SEE_OTHER);
    }
}
