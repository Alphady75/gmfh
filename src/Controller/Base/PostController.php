<?php

namespace App\Controller\Base;

use App\Entity\Dto\Post as DtoPost;
use App\Entity\Post;
use App\Entity\Signaler;
use App\Form\Base\ContactUserType;
use App\Form\Base\SignalerPostType;
use App\Form\Dto\VisitePostType;
use App\Repository\PostRepository;
use App\Service\ConversationService;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Exception\Config\Filter\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annonces')]
class PostController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository,
        private EntityManagerInterface $entityManager,
        private ConversationService $conversationService,
        private MailerService $mailerService
    ) {
    }

    #[Route('/', name: 'posts')]
    public function index(Request $request): Response
    {
        $clear = false;
        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0)
            $clear = true;

        $search = new DtoPost();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(VisitePostType::class, $search);
        $form->handleRequest($request);
        $posts = $this->postRepository->visiteurFilter($search, $this->getUser());

        return $this->render('post/index.html.twig', [
            'clear' => $clear,
            'posts' => $posts,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/details/{slug}', name: 'post_details', methods: ['GET', 'POST'])]
    public function details(Post $post, Request $request): Response
    {
        if (!$post OR $post->getOnline() == 0){
            $this->addFlash('warning', "Le contenu que vous rechercher est introuvable");
            return $this->redirectToRoute('posts');
        }

        $signaler = new Signaler();
        $signalerForm = $this->createForm(SignalerPostType::class, $signaler, []);
        $signalerForm->handleRequest($request);

        if ($signalerForm->isSubmitted() && $signalerForm->isValid()) {
            if (!$this->getUser())
                return $this->redirectToRoute('app_login');

            $signaler->setUser($this->getUser());
            $signaler->setPost($post);
            $this->entityManager->persist($signaler);
            $this->entityManager->flush();
            $this->addFlash('success', "Votre requette");
            return $this->redirectToRoute('offre_details', ['slug' => $post->getSlug()]);
        }

        $similars = $this->postRepository->findBy(['souscategorie' => $post->getSouscategorie()], ['created' => 'DESC'], 5);

        # Open chat
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
                $post->getUser()
            );

            # Open chat now...
            if ($loginUser) {
                # Check if conversation
                $checkconversation = $this->conversationService->checkPostConversation($loginUser, $post->getUser(), $post);

                # Open Chat
                if ($checkconversation) {
                    return $this->redirectToRoute('conversation_details', ['token' => $checkconversation->getToken()]);
                }

                $conversation = $this->conversationService->createPostConversation(
                    $loginUser,
                    $post->getUser(),
                    $loginUser,
                    $contactForm->get('message')->getData(),
                    $post,
                    $post->getUser(),
                );


                return $this->redirectToRoute('conversation_details', ['token' => $conversation->getToken()]);
            }

            $this->addFlash('warning', 'Vous devez vous connecter afin de soumettre votre message');
        }

        return $this->render('post/details.html.twig', [
            'post' => $post,
            'similars' => $similars,
            'signalerForm' => $signalerForm->createView(),
            'contactForm' => $contactForm->createView(),
        ]);
    }

    #[Route('/categorie/{slug}', name: 'categorie_posts')]
    public function categories(): Response
    {
        return $this->render('post/categories.html.twig', [
            'posts' => $this->postRepository->findBy(['created' => 'DESC', 'bloquer' => 0, 'online' => 1]),
        ]);
    }
}
