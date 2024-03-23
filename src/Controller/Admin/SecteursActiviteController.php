<?php

namespace App\Controller\Admin;

use App\Entity\SecteursActivite;
use App\Form\SecteursActiviteType;
use App\Repository\SecteursActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/secteurs/activite')]
class SecteursActiviteController extends AbstractController
{
    #[Route('/', name: 'admin_secteurs_activite_index', methods: ['GET'])]
    public function index(SecteursActiviteRepository $secteursActiviteRepository): Response
    {
        return $this->render('admin/secteurs_activite/index.html.twig', [
            'secteurs_activites' => $secteursActiviteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_secteurs_activite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $secteursActivite = new SecteursActivite();
        $form = $this->createForm(SecteursActiviteType::class, $secteursActivite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($secteursActivite);
            $entityManager->flush();

            return $this->redirectToRoute('admin_secteurs_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/secteurs_activite/new.html.twig', [
            'secteurs_activite' => $secteursActivite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_secteurs_activite_show', methods: ['GET'])]
    public function show(SecteursActivite $secteursActivite): Response
    {
        return $this->render('admin/secteurs_activite/show.html.twig', [
            'secteurs_activite' => $secteursActivite,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_secteurs_activite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SecteursActivite $secteursActivite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SecteursActiviteType::class, $secteursActivite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_secteurs_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/secteurs_activite/edit.html.twig', [
            'secteurs_activite' => $secteursActivite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_secteurs_activite_delete', methods: ['POST'])]
    public function delete(Request $request, SecteursActivite $secteursActivite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$secteursActivite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($secteursActivite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_secteurs_activite_index', [], Response::HTTP_SEE_OTHER);
    }
}
