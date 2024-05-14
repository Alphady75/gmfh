<?php

namespace App\Controller\Admin;

use App\Entity\Dto\Post as DtoPost;
use App\Entity\Post;
use App\Form\Dto\PostType as DtoPostType;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/post')]
class PostController extends AbstractController
{
    public function __construct(private PostRepository $postRepository, private SluggerInterface $slugger)
    {
    }

    #[Route('/', name: 'admin_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository, Request $request): Response
    {
        $search = new DtoPost();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(DtoPostType::class, $search);
        $form->handleRequest($request);
        $posts = $postRepository->adminFilter($search, $this->getUser());

        return $this->render('user/post/index.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'admin_post_new', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('admin_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('admin/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}', name: 'admin_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
