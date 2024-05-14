<?php

namespace App\Controller\User;

use App\Entity\Favoris;
use App\Repository\FavorisRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/partenaire/favoris')]
class FavorisController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerService $mailer,
        private FavorisRepository $favorisRepository,
        private PaginatorInterface $paginator
    ) {
    }

    #[Route('/annonces', name: 'partenaire_posts_favoris')]
    public function annonces(Request $request): Response
    {
        $favoris = $this->paginator->paginate(
            $this->favorisRepository->findBy(['user' => $this->getUser(), 'element' => 'post'], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('user/favoris/posts.html.twig', [
            'favoris' => $favoris
        ]);
    }

    #[Route('/offres', name: 'partenaire_offres_favoris')]
    public function offres(Request $request): Response
    {
        $favoris = $this->paginator->paginate(
            $this->favorisRepository->findBy(['user' => $this->getUser(), 'element' => 'offre'], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('user/favoris/offres.html.twig', [
            'favoris' => $favoris
        ]);
    }

    #[Route('/users', name: 'partenaire_users_favoris')]
    public function users(Request $request): Response
    {
        $favoris = $this->paginator->paginate(
            $this->favorisRepository->findBy(['user' => $this->getUser(), 'element' => 'user'], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('user/favoris/users.html.twig', [
            'favoris' => $favoris
        ]);
    }

    #[Route('/{id}', name: 'partenaire_favoris_delete', methods: ['POST'])]
    public function delete(Request $request, Favoris $favori, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favori->getId(), $request->request->get('_token'))) {
            $entityManager->remove($favori);
            $entityManager->flush();
            $this->addFlash('success', "L'élément a bien été supprimer en favoris");
        }

        return $this->redirectToRoute('partenaire_favoris', [], Response::HTTP_SEE_OTHER);
    }
}
