<?php

namespace App\Security;

use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
        private UserService $userService
    ) {
        $this->urlGenerator = $urlGenerator;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        $defaultUrl = $this->urlGenerator->generate('user_redirect', [], 301);

        # get Login User
        $loginUser = $this->getCredentials($request);

        # get register user
        $registerUser = $this->userService->getUserEmailInSession();

        # Email to considère
        $emailToConsider = $loginUser['email'] == null ? $registerUser : $loginUser['email'];
        
        # Save him in session
        $this->userService->setUserEmailInSession($emailToConsider);

        # Check if profil cmoplete
        $checkUser = $this->userRepository->findOneBy(['email' => $emailToConsider]);

        # If is registration redirect to confirm code
        if ($loginUser['email'] == null) {
            $defaultUrl = $this->urlGenerator->generate("app_confirm_code", [], 301);
            return new RedirectResponse($defaultUrl);
        }

        if ($checkUser->getCompleted() == false || $checkUser->getCompleted() == null) {
            return new RedirectResponse($this->urlGenerator->generate("register_complete_compte", [], 301));
        }

        # Redirection
        return new RedirectResponse($defaultUrl);
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
        ];

        $request->getSession()->set(Security::LAST_USERNAME, $credentials['email']);

        return $credentials;
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
