<?php

namespace App\Controller\User;

use App\Entity\Candidature;
use App\Entity\Dto\Candidature as DtoCandidature;
use App\Form\CandidatureType;
use App\Form\Dto\CandidatureType as DtoCandidatureType;
use App\Repository\CandidatureRepository;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/candidatures')]
class CandidatureController extends AbstractController
{
    public function __construct(
        private ConversationRepository $conversationRepository,
        private CandidatureRepository $candidatureRepository,
        private PaginatorInterface $paginator
    ) {
    }

    #[Route('/liste', name: 'user_candidatures', methods: ['GET'])]
    public function index(CandidatureRepository $candidatureRepository,  Request $request): Response
    {
        $clear = false;
        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0)
            $clear = true;

        $search = new DtoCandidature();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(DtoCandidatureType::class, $search);
        $form->handleRequest($request);
        $candidatures = $candidatureRepository->auteurFilter($search, $this->getUser());

        return $this->render('user/candidature/index.html.twig', [
            'candidatures' => $candidatures,
            'clear' => $clear,
            'path' => 'user_candidatures',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/appliquee', name: 'user_candidatures_appliquer', methods: ['GET'])]
    public function appliquer(Request $request): Response
    {
        $clear = false;
        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0)
            $clear = true;

        $search = new DtoCandidature();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(DtoCandidatureType::class, $search);
        $form->handleRequest($request);
        $candidatures = $this->candidatureRepository->aappliquerFilter($search, $this->getUser());

        return $this->render('user/candidature/appliquer.html.twig', [
            'candidatures' => $candidatures,
            'form' => $form->createView(),
            'clear' => $clear,
            'path' => 'user_candidatures_appliquer',
        ]);
    }

    #[Route('/{token}', name: 'user_candidature_show', methods: ['GET'])]
    public function show(Candidature $candidature): Response
    {
        $conversation = $this->conversationRepository->findOneBy(['candidature' => $candidature]);

        return $this->render('user/candidature/show.html.twig', [
            'candidature' => $candidature,
            'conversation' => $conversation,
            'messages' => $conversation->getMessages()
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
        if ($this->isCsrfTokenValid('delete' . $candidature->getId(), $request->request->get('_token'))) {
            $entityManager->remove($candidature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_candidatures', [], Response::HTTP_SEE_OTHER);
    }
}
