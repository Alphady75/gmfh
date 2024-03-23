<?php

namespace App\Controller\User;

use App\Entity\Dto\Post as DtoPost;
use App\Entity\Post;
use App\Form\Dto\PostType as DtoPostType;
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
        $search = new DtoPost();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(DtoPostType::class, $search);
        $form->handleRequest($request);
        $posts = $postRepository->auteurFilter($search, $this->getUser());

        return $this->render('user/post/index.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/publier-une-annonce', name: 'user_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setSlug(strtolower($this->slugger->slug($form->get('name')->getData())));
            $post->setUser($this->getUser());
            $post->setOnline(true);
            $entityManager->persist($post);
            $entityManager->flush();

            $post->setSlug($post->getSlug() . '-' . $post->getId());
            $entityManager->flush();

            $this->addFlash('success', "Le contenu a bien été enregistrer");

            # Booster if true
            $boosted = $form->get('boosted')->getData();
            if ($boosted)
                return $this->redirectToRoute('booster_post', ['slug' => $post->getSlug()], 301);

            return $this->redirectToRoute('user_posts', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'user_post_show', methods: ['GET'])]
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

            $this->addFlash('success', "Le contenu a bien été enregistrer");
            return $this->redirectToRoute('user_posts', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_posts', [], Response::HTTP_SEE_OTHER);
    }
}
