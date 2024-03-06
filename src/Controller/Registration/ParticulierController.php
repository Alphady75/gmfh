<?php

namespace App\Controller\Registration;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\GenererCodeService;
use App\Service\MailerService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

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
        private EntityManagerInterface $entityManager
    ) {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/', name: 'particulier_register', methods: ['POST', 'GET'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
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

            $user->setCodeIsVerified($codeConfirmation);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            # Envoie du mail à l'utilisateur
            $this->mailerService->sendCodeToConfirmEmailCode($user->getEmail(), $codeConfirmation);

            return $this->redirectToRoute('app_confirm_code', [], 301);

            /*return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );*/
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'compte' => 'PARTICULIER'
        ]);
    }
}
