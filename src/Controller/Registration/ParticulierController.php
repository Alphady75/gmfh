<?php

namespace App\Controller\Registration;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use App\Service\GenererCodeService;
use App\Service\MailerService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/inscription-particulier')]
class ParticulierController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(
        EmailVerifier $emailVerifier,
        private GenererCodeService $genererCodeService,
        private UserService $userService,
        private MailerService $mailerService,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger
    ) {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/', name: 'particulier_register', methods: ['POST', 'GET'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, LoginFormAuthenticator $authenticator, UserAuthenticatorInterface $userAuthenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Génération & enregistrement du code de vérification email en session
            $codeConfirmation = $this->genererCodeService->generateUniqueCode();
            $this->genererCodeService->setCodeInSession($codeConfirmation);
            
            # Save user Email in session
            $this->userService->setUserEmailInSession($form->get('email')->getData());

            $user->setRoles(['ROLE_PARTICULIER']);
            $user->setCompte('PARTICULIER');
            # encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
            $noms = $form->get('nom')->getData() . ' ' . $form->get('prenom')->getData();
            $user->setCodeIsVerified($codeConfirmation);
            $user->setCompleted(false);
            $user->setNameSlug($this->slugger->slug(strtolower($noms)));
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            # Envoie du mail à l'utilisateur
            $this->mailerService->sendCodeToConfirmEmailCode($user->getEmail(), $codeConfirmation);

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'compte' => 'PARTICULIER'
        ]);
    }
}
