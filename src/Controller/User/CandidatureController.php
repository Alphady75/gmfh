<?php

namespace App\Controller\User;

use App\Entity\Candidature;
use App\Entity\Dto\Candidature as DtoCandidature;
use App\Form\Dto\CandidatureType;
use App\Repository\CandidatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/candidature')]
class CandidatureController extends AbstractController
{
    #[Route('/liste', name: 'user_candidatures', methods: ['GET'])]
    public function index(CandidatureRepository $candidatureRepository,  Request $request): Response
    {
        $search = new DtoCandidature();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(CandidatureType::class, $search);
        $form->handleRequest($request);
        $candidatures = $candidatureRepository->auteurFilter($search, $this->getUser());

        return $this->render('user/candidature/index.html.twig', [
            'candidatures' => $candidatures,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'user_candidature_show', methods: ['GET'])]
    public function show(Candidature $candidature): Response
    {
        return $this->render('user/candidature/show.html.twig', [
            'candidature' => $candidature,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_candidature_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_candidatures', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/candidature/edit.html.twig', [
            'candidature' => $candidature,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user_candidature_delete', methods: ['POST'])]
    public function delete(Request $request, Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidature->getId(), $request->request->get('_token'))) {
            $entityManager->remove($candidature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_candidatures', [], Response::HTTP_SEE_OTHER);
    }
}
