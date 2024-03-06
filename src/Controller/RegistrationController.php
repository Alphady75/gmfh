<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\User\CheckCodeType;
use App\Form\User\RequestCodeType;
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
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
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

    #[Route('/inscription', name: 'app_register', methods: ['POST', 'GET'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator): Response
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

            $user->setRoles(['ROLE_PERSONNEL']);
            $user->setCompte('PERSONNEL');
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
            'compte' => 'PERSONEL'
        ]);
    }

    #[Route('/confirm-code-activation', name: 'app_confirm_code')]
    public function verifyCode(Request $request): Response
    {
        # Save user Email in session
        $emailInSession = $this->userService->getUserEmailInSession();
        if ($emailInSession == null)
            return $this->redirectToRoute('app_register_request_code', [], 301);

        $form = $this->createForm(CheckCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var User */
            $user = $this->userService->getUserByCodeAndEmailInSession($form->get('code')->getData());

            if ($user) {

                # Updated user
                $user->setIsVerified(true);
                $this->entityManager->flush();

                # Redirect where to connect then complete profil
                # return $this->redirectToRoute('app_login', ['redirect' => 'user_complete_compte']);
                return $this->redirectToRoute('register_success', [], 301);

            } else {
                $this->addFlash('danger', 'Code de confirmation du compte');
            }
        }

        return $this->render('registration/verify_code.html.twig', [
            'form' => $form->createView(),
            'userEmail' => $emailInSession,
        ]);

        return new Response();
    }

    #[Route('/inscription-reussi', name: 'register_success')]
    public function registrationSuccess(Request $request): Response
    {
        # Save user Email in session
        $emailInSession = $this->userService->getUserEmailInSession();
        if ($emailInSession == null)
            return $this->redirectToRoute('app_register_request_code', [], 301);

        return $this->render('registration/success.html.twig', [
            'userEmail' => $emailInSession,
        ]);

        return new Response();
    }

    #[Route('/request-code-activation-compte', name: 'app_register_request_code')]
    public function requestCode(Request $request): Response
    {
        $form = $this->createForm(RequestCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # Find user
            $user = $this->userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if ($user) {
            
                # Générate new code
                $newCode = $this->genererCodeService->generateUniqueCode();

                # Set user in session
                $this->userService->setUserEmailInSession($user->getEmail());

                # Mise à jour du code de confirmation email
                $user->setCodeIsVerified($newCode);
                $this->entityManager->flush();

            }else {
                # Set user from form in session
                $this->userService->setUserEmailInSession($form->get('email')->getData());
            }

            # Redirect where to connect then complete profil
            return $this->redirectToRoute('app_confirm_code', [], 301);
        }

        return $this->render('registration/request_code.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response();
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }

    #[Route('/resend-code', name: 'app_send_new_code', methods: ['POST'])]
    public function resendCode()
    {
        $userEmail = $this->userService->getUserEmailInSession();

        if ($userEmail) {
            $user = $this->userRepository->findOneBy(['email' => $userEmail]);
            $code = $this->genererCodeService->generateUniqueCode();
            $this->mailerService->sendCodeToConfirmEmailCode($userEmail, $code);

            # Updated user code to database
            $user->setCodeIsVerified($code);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_confirm_code', [], 301);
        }

        return null;
    }
}
