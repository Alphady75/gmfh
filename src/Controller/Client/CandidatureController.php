<?php

namespace App\Controller\Client;

use App\Entity\Candidature;
use App\Repository\CandidatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client-espace/candidatures')]
class CandidatureController extends AbstractController
{
    public function __construct(
        private CandidatureRepository $candidatureRepository,
        private EntityManagerInterface $entityManagerInterface,
        private PaginatorInterface $paginator,
    ) {
    }

    #[Route('/', name: 'client_candidatures')]
    public function clientsCandidatures(Request $request): Response
    {
        $user = $this->getUser();

        $candidatures = $this->paginator->paginate(
            $this->candidatureRepository->findBy(['user' => $user], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('client/candidature/index.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }

    #[Route('/{token}', name: 'client_candidatures_details')]
    public function clientsCandidaturesDetaisl(Candidature $candidature, Request $request): Response
    {
        $user = $this->getUser();

        return $this->render('client/candidature/details.html.twig', [
            'candidature' => $candidature,
            'user' => $user,
        ]);
    }
}
