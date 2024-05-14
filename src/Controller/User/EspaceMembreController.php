<?php

namespace App\Controller\User;

use App\Entity\Activite;
use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\User\BecomeEntrepriseType;
use App\Form\User\BecomeParticulierOrEntrepriseType;
use App\Form\User\ProfilEntrepriseType;
use App\Form\User\ProfilParticulierType;
use App\Form\User\ProfilPersonnelType;
use App\Repository\ActiviteRepository;
use App\Repository\FavorisRepository;
use App\Repository\OffreRepository;
use App\Repository\PostRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/partenaire')]
class EspaceMembreController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerService $mailer,
        private FavorisRepository $favorisRepository,
        private PaginatorInterface $paginator,
        private TokenStorageInterface $tokenStorage,
        private OffreRepository $offreRepository,
        private PostRepository $postRepository,
    ) {
    }

    #[Route('/espace-membre', name: 'user_espace')]
    public function index(ActiviteRepository $activiteRepository, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        # Offres d'emplois
        $offres = $this->paginator->paginate(
            $this->offreRepository->findBy(['user' => $user, 'complet' => 1, 'bloquer' => 0, 'status' => 'Actif'], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            6
        );

        # Annonces
        $posts = $this->paginator->paginate(
            $this->postRepository->findBy(['user' => $user, 'bloquer' => 0], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('user/espace/espace_membre.html.twig', [
            'offres' => $offres,
            'posts' => $posts,
            'user' => $user,
        ]);
    }

    #[Route('/completer-mon-compte', name: 'user_complete_compte')]
    public function completeCompte(Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();
        $userCompte = $user->getCompte();
        $formType = ProfilPersonnelType::class;

        if ($userCompte == 'PARTICULIER') {
            $formType = ProfilParticulierType::class;
        }

        if ($userCompte == 'ENTREPRISE') {
            $formType = ProfilEntrepriseType::class;
        }

        $form = $this->createForm($formType, $user, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setCompleted(true);
            $this->entityManager->flush();

            $this->addFlash('success', "Votre compte à bien été mis à jour");
            return $this->redirectToRoute('user_espace', [], 301);
        }

        return $this->render('user/espace/complete_compte.html.twig', [
            'form' => $form->createView(),
            'userCompte' => $userCompte,
        ]);
    }

    #[Route('/changer-statut-compte', name: 'user_change_compte', methods: ['POST', 'GET'])]
    public function changeCompteStatut(Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();

        $formType = BecomeParticulierOrEntrepriseType::class;
        $currentStatut = $user->getCompte();

        # Check user compte
        if ($currentStatut == "PARTICULIER")
            $formType = BecomeEntrepriseType::class;

        $form = $this->createForm($formType, $user, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newStatut = $form->get('compte')->getData();

            if ($newStatut == "PARTICULIER") {
                $user->setRoles(['ROLE_PARTICULIER']);
            }

            if ($newStatut == "ENTREPRISE") {
                $user->setRoles(['ROLE_ENTREPRISE']);
            }

            # Reset completed
            $user->setCompleted(false);
            $this->entityManager->flush();

            # Save activity
            $activite = new Activite();
            $activite->setObjet('Changement de statut de votre compte');
            $activite->setUser($user);
            $activite->setRaison($form->get('raisons')->getData());
            $this->entityManager->persist($activite);
            $this->entityManager->flush();

            # Send mail to User
            $this->mailer->sendStatutCompteChanged($user->getEmail(), $currentStatut, $newStatut, $activite);

            # Redirect to login to config compte
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('user/espace/change_compte.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/mettre-a-jour-mes-acces', name: 'user_update_acces')]
    public function updateProfile(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class, $user, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode(hash) the plain password, and set it.
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été réinitialiser');
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('user/espace/access.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-votre-compte', name: 'user_delete')]
    public function supprimerCompte(): Response
    {
        return $this->render('user/espace/delete_compte.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/supprimer-votre-compte/{id}', name: 'user_delete_compte', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Votre compte a bien été supprimé');

        // Récupérer le token de l'utilisateur actuellement connecté
        $token = $this->tokenStorage->getToken();

        if ($token !== null) {
            // Invalider la session
            $this->tokenStorage->setToken(null);
        }

        // Rediriger l'utilisateur vers une autre page, par exemple la page d'accueil
        return $this->redirectToRoute('app_login');
    }
}
