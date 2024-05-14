<?php

namespace App\Controller\Admin;

use App\Entity\Offre;
use App\Entity\Post;
use App\Entity\Signaler;
use App\Form\SignalerType;
use App\Repository\SignalerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/signaler')]
class SignalerController extends AbstractController
{
    public function __construct(private PaginatorInterface $paginator)
    {
    }
    #[Route('/', name: 'admin_signaler_index', methods: ['GET'])]
    public function index(SignalerRepository $signalerRepository, Request $request): Response
    {
        $signalers = $this->paginator->paginate(
            $signalerRepository->findBy([], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('admin/signaler/index.html.twig', [
            'signalers' => $signalers,
        ]);
    }

    #[Route('/new', name: 'admin_signaler_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $signaler = new Signaler();
        $form = $this->createForm(SignalerType::class, $signaler);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($signaler);
            $entityManager->flush();

            return $this->redirectToRoute('admin_signaler_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/signaler/new.html.twig', [
            'signaler' => $signaler,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_signaler_show', methods: ['GET'])]
    public function show(Signaler $signaler): Response
    {
        return $this->render('admin/signaler/show.html.twig', [
            'signaler' => $signaler,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_signaler_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Signaler $signaler, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SignalerType::class, $signaler);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_signaler_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/signaler/edit.html.twig', [
            'signaler' => $signaler,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_signaler_delete', methods: ['POST'])]
    public function delete(Request $request, Signaler $signaler, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $signaler->getId(), $request->request->get('_token'))) {
            $entityManager->remove($signaler);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_signaler_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/offre-bloque/{id}', name: 'admin_gere_offre', methods: ['POST'])]
    public function bloqueOffre(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('gereoffre' . $offre->getId(), $request->request->get('_token'))) {
            $statut = $offre->getBloquer() == 1 ? 'Bloquer' : 'Débloquer';
            if ($offre->getBloquer() == 1) {
                $offre->setBloquer(0);
            } else {
                $offre->setBloquer(1);
            }
            $entityManager->flush();
            $this->addFlash('success', "L'élément à bien été $statut");
        }
        return $this->redirectToRoute('admin_signaler_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/post-bloque/{id}', name: 'admin_block_post', methods: ['POST'])]
    public function bloquePost(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('bloquepost' . $post->getId(), $request->request->get('_token'))) {
            $statut = $post->getBloquer() == 1 ? 'Bloquer' : 'Débloquer';
            if ($post->getBloquer() == 1) {
                $post->setBloquer(0);
            } else {
                $post->setBloquer(1);
            }
            $entityManager->flush();
            $this->addFlash('success', "le service à bien été $statut");
        }
        return $this->redirectToRoute('admin_signaler_index', [], Response::HTTP_SEE_OTHER);
    }
}
