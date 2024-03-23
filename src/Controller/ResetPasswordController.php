<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordVerifyCodeFormType;
use App\Repository\UserRepository;
use App\Service\GenererCodeService;
use App\Service\MailerService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;
    private $entityManager;

    public function __construct(
        ResetPasswordHelperInterface $resetPasswordHelper,
        EntityManagerInterface $entityManager,
        private GenererCodeService $genererCodeService,
        private UserService $userService,
        private UserRepository $userRepository,
        private MailerService $mailerService
    ) {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->entityManager = $entityManager;
    }

    /**
     * Display & process form to request a password reset.
     */
    #[Route('/', name: 'app_forgot_password_request')]
    public function request(Request $request): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Recupération email utilisateur
            $email = $form->get('email')->getData();

            # Recupération du compte utilisateur avec emil
            $user = $this->userRepository->findOneBy(['email' => $email]);

            # Génération du code
            $code = $this->genererCodeService->generateUniqueCode();

            # Enregistrement du code dans la séssion
            $this->genererCodeService->setCodeInSession($code);

            # Envoie du code par mail
            if ($user) {
                $this->mailerService->sendReinitialisationPasswordCode($user->getEmail(), $code);

                # Mise à jour du code de réinitialisation de l'utilisateur
                $user->setResetPasswordCode($code);
                $this->entityManager->flush();
            }

            # Enregistrement de l'email de l'utilisateur dans la session
            $this->userService->setUserEmailInSession($email);

            return $this->redirectToRoute('app_password_request_verify_code', [], 301);
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }


    #[Route('/verification-code-reinitialisation', name: 'app_password_request_verify_code')]
    public function verifyCode(Request $request): Response
    {
        $codeSession = $this->genererCodeService->getCodeInSession();
        $emailSession = $this->userService->getUserEmailInSession();
        $form = $this->createForm(ResetPasswordVerifyCodeFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userService->getUserByResetPassCodeAndEmailInSession($codeSession);

            if ($user == null) {
                $this->addFlash('danger', 'Code de réinitialisation du mot de passe incorect');
            }

            if ($user) {
                return $this->redirectToRoute('app_reinitialiser_password', [], 301);
            }
        }

        return $this->render('reset_password/verify_code.html.twig', [
            'requestForm' => $form->createView(),
            'emailUser' => $emailSession,
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     */
    #[Route('/reinitialiser-mot-de-passe', name: 'app_reinitialiser_password')]
    public function reinitialiser(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $codeSession = $this->genererCodeService->getCodeInSession();
        /** @var User */
        $user = $this->userService->getUserByResetPassCodeAndEmailInSession($codeSession);

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            $this->addFlash('success', 'Votre mot de passe a bien été réinitialiser');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    #[Route('/resend-code', name: 'app_send_new_resetpass_code', methods: ['POST'])]
    public function resendCode()
    {
        $userEmail = $this->userService->getUserEmailInSession();

        if ($userEmail) {
            $user = $this->userRepository->findOneBy(['email' => $userEmail]);
            $code = $this->genererCodeService->generateUniqueCode();
            $this->mailerService->sendReinitialisationPasswordCode($userEmail, $code);

            if ($user) {
                # Updated user code to database
                $user->setResetPasswordCode($code);
                $this->entityManager->flush();

                # Enregistrement du code dans la séssion
                $this->genererCodeService->setCodeInSession($code);
            }

            return $this->redirectToRoute('app_password_request_verify_code', [], 301);
        }

        return null;
    }

    /**
     * Confirmation page after a user has requested a password reset.
     */
    #[Route('/check-email', name: 'app_check_email')]
    public function checkEmail(): Response
    {
        // Generate a fake token if the user does not exist or someone hit this page directly.
        // This prevents exposing whether or not a user was found with the given email address or not
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     */
    #[Route('/reset/{token}', name: 'app_reset_password')]
    public function reset(Request $request, UserPasswordHasherInterface $userPasswordHasher, string $token = null): Response
    {
        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));

            return $this->redirectToRoute('app_forgot_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->redirectToRoute('app_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            // If you want to tell the user why a reset email was not sent, uncomment
            // the lines below and change the redirect to 'app_forgot_password_request'.
            // Caution: This may reveal if a user is registered or not.
            //
            // $this->addFlash('reset_password_error', sprintf(
            //     'There was a problem handling your password reset request - %s',
            //     $e->getReason()
            // ));

            return $this->redirectToRoute('app_check_email');
        }

        $email = (new TemplatedEmail())
            ->from(new Address('gamfah@gmail.com', 'gamfah'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ]);

        $mailer->send($email);

        // Store the token object in session for retrieval in check-email route.
        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('app_check_email');
    }
}
