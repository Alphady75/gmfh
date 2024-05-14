<?php

namespace App\Controller\Admin;

use App\Entity\Candidature;
use App\Entity\Dto\Candidature as DtoCandidature;
use App\Form\Dto\CandidatureType as DtoCandidatureType;
use App\Repository\CandidatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/candidature')]
class CandidatureController extends AbstractController
{
    public function __construct(
        private CandidatureRepository $candidatureRepository,
        private PaginatorInterface $paginator
    ) {
    }

    #[Route('/', name: 'admin_candidature_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $clear = false;
        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0)
            $clear = true;

        $search = new DtoCandidature();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(DtoCandidatureType::class, $search);
        $form->handleRequest($request);
        $candidatures = $this->candidatureRepository->adminFilter($search, $this->getUser());

        return $this->render('user/candidature/index.html.twig', [
            'candidatures' => $candidatures,
            'clear' => $clear,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_candidature_show', methods: ['GET'])]
    public function show(Candidature $candidature): Response
    {
        return $this->render('user/candidature/show.html.twig', [
            'candidature' => $candidature,
        ]);
    }

    #[Route('/{id}', name: 'admin_candidature_delete', methods: ['POST'])]
    public function delete(Request $request, Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $candidature->getId(), $request->request->get('_token'))) {
            $entityManager->remove($candidature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_candidature_index', [], Response::HTTP_SEE_OTHER);
    }
}
