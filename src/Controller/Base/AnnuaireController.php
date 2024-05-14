<?php

namespace App\Controller\Base;

use App\Entity\Dto\User as DtoUser;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\Base\ContactUserType;
use App\Form\Dto\VisiteUserType;
use App\Repository\OffreRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\ConversationService;
use App\Service\MailerService;
use App\Service\UserService;
use Knp\Component\Pager\PaginatorInterface;
use Liip\ImagineBundle\Exception\Config\Filter\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annuaire')]
class AnnuaireController extends AbstractController
{
    public function __construct(
        private OffreRepository $offreRepository,
        private PostRepository $postRepository,
        private PaginatorInterface $paginator,
        private UserRepository $userRepository,
        private ConversationService $conversationService,
        private MailerService $mailerService,
        private UserService $userService
    ) {
    }

    #[Route('/', name: 'annuaire')]
    public function allUsers(Request $request): Response
    {
        $clear = false;
        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0)
            $clear = true;

        $search = new DtoUser();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(VisiteUserType::class, $search);
        $form->handleRequest($request);
        $users = $this->userRepository->visiteurSearch($search);

        return $this->render('annuaire/index.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
            'clear' => $clear
        ]);
    }

    #[Route('/villes/{slug}', name: 'annuaire_ville')]
    public function usersByVilles(Ville $ville, Request $request): Response
    {
        $clear = false;
        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0)
            $clear = true;

        $search = new DtoUser();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(VisiteUserType::class, $search);
        $form->handleRequest($request);
        $users = $this->userRepository->visiteurSearchByLocation($search, $ville);

        return $this->render('annuaire/ville.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
            'clear' => $clear,
            'ville' => $ville,
        ]);
    }

    #[Route('/profile/{nameSlug}', name: 'annuaire_profile', methods: ['GET', 'POST'])]
    public function profile(User $user, Request $request): Response
    {
        if (!$user)
            return new NotFoundException("Une erreur s'est produit aucun resultat pour votre recherche");

        # Check if profil is vue
        $this->userService->checkVueProfile($user);

        # Get logedin User
        /** @var User */
        $loginUser = $this->getUser();

        if ($loginUser) {
            if ($loginUser->getCompte() == 'ENTREPRISE') {
                $loginUser->setNom($loginUser->getSociete());
            } else {
                $loginUser->setNom($loginUser->getNom() . ' ' . $loginUser->getPrenom());
            }
        }

        # Contact User
        $contactForm = $this->createForm(ContactUserType::class, $loginUser, []);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {

            # Send mail to Destinataire
            $this->mailerService->sendContactUserMail(
                $contactForm->get('nom')->getData(),
                $contactForm->get('email')->getData(),
                $contactForm->get('message')->getData(),
                $user
            );

            # Open chat now...
            if ($loginUser) {
                # Check if conversation
                $checkconversation = $this->conversationService->checkConversation($loginUser, $user);

                # Open Chat
                if ($checkconversation) {
                    return $this->redirectToRoute('conversation_details', ['token' => $checkconversation->getToken()]);
                }

                $conversation = $this->conversationService->createConversation(
                    $loginUser,
                    $user,
                    $loginUser,
                    $contactForm->get('message')->getData(),
                    null,
                    $user
                );


                return $this->redirectToRoute('conversation_details', ['token' => $conversation->getToken()]);
            }

            $this->addFlash('warning', 'Vous devez vous connecter afin de soumettre votre message');
        }

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

        return $this->render('annuaire/profile.html.twig', [
            'user' => $user,
            'offres' => $offres,
            'posts' => $posts,
            'contactForm' => $contactForm->createView(),
        ]);
    }
}
