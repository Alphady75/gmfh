<?php

namespace App\Controller\Base;

use App\Entity\Favoris;
use App\Entity\Offre;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\FavorisRepository;
use App\Repository\OffreRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/favoris')]
class FavorisController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PostRepository $postRepository,
        private OffreRepository $offreRepository,
        private FavorisRepository $favorisRepository
    ) {
    }

    #[Route('/offre-add/{id}', name: 'favoris_offre_new', methods: ['GET', 'POST'])]
    public function addOffre(Offre $offre, Request $request): Response
    {
        $user = $this->getUser();
        if (!$offre) {
            dd('Aucun offre');
        }

        if (!$user) return $this->json([
            'code' => 404,
            'message' => 'Vous devez être connecter!'
        ], 403);

        if ($offre->isAddedByUser($user)) {

            $favori = $this->favorisRepository->findOneBy([
                'offre' => $offre,
                'user' => $user
            ]);

            $this->entityManager->remove($favori);
            $this->entityManager->flush();
            /*return $this->json([
                'code' => 200,
                'message' => 'Favoris supprimer avec succès',
            ], 200);*/
        } else {
            $favori = new Favoris;

            $favori->setOffre($offre);
            $favori->setUser($user);
            $favori->setElement('offre');
            $this->entityManager->persist($favori);
            $this->entityManager->flush();
            /*return $this->json([
                'code' => 200,
                'message' => 'Favoris ajouter avec succès',
            ], 200);*/
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/post-add/{id}', name: 'favoris_post_new', methods: ['GET', 'POST'])]
    public function addPost(Post $post, Request $request): Response
    {
        $user = $this->getUser();
        if (!$post) {
            dd('Aucun post');
        }

        if (!$user) return $this->json([
            'code' => 404,
            'message' => 'Vous devez être connecter!'
        ], 403);

        if ($post->isAddedByUser($user)) {

            $favori = $this->favorisRepository->findOneBy([
                'post' => $post,
                'user' => $user
            ]);

            $this->entityManager->remove($favori);
            $this->entityManager->flush();
            /*return $this->json([
                'code' => 200,
                'message' => 'Favoris supprimer avec succès',
            ], 200);*/
        } else {
            $favori = new Favoris;

            $favori->setPost($post);
            $favori->setUser($user);
            $favori->setElement('post');
            $this->entityManager->persist($favori);
            $this->entityManager->flush();
            /*return $this->json([
                'code' => 200,
                'message' => 'Favoris ajouter avec succès',
            ], 200);*/
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/user-add/{id}', name: 'favoris_user_new', methods: ['GET', 'POST'])]
    public function addUser(User $user, Request $request): Response
    {
        $logedInUser = $this->getUser();

        if (!$logedInUser) return $this->json([
            'code' => 404,
            'message' => 'Vous devez être connecter!'
        ], 403);

        if ($user->isAddedByUser($user)) {

            $favori = $this->favorisRepository->findOneBy([
                'user' => $user,
                'user' => $user
            ]);

            $this->entityManager->remove($favori);
            $this->entityManager->flush();
            /*return $this->json([
                'code' => 200,
                'message' => 'Favoris supprimer avec succès',
            ], 200);*/
        } else {
            $favori = new Favoris;

            $favori->setuser($user);
            $favori->setUser($user);
            $favori->setElement('user');
            $this->entityManager->persist($favori);
            $this->entityManager->flush();
            /*return $this->json([
                'code' => 200,
                'message' => 'Favoris ajouter avec succès',
            ], 200);*/
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
