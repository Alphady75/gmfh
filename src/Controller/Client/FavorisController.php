<?php

namespace App\Controller\Client;

use App\Entity\Favoris;
use App\Repository\FavorisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client/favoris')]
class FavorisController extends AbstractController
{
    public function __construct(private PaginatorInterface $paginator, private FavorisRepository $favorisRepository)
    {
        
    }

    #[Route('/', name: 'client_favoris', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $favoris = $this->paginator->paginate(
            $this->favorisRepository->findBy(['user' => $this->getUser()], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('client/favoris/index.html.twig', [
            'favoris' => $favoris,
        ]);
    }

    #[Route('/{id}', name: 'client_favoris_delete', methods: ['POST'])]
    public function delete(Request $request, Favoris $favori, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favori->getId(), $request->request->get('_token'))) {
            $entityManager->remove($favori);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_favoris', [], Response::HTTP_SEE_OTHER);
    }
}
