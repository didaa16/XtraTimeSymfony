<?php

namespace App\Security;

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

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $pseudo = $request->request->get('pseudo', '');

        $request->getSession()->set(Security::LAST_USERNAME, $pseudo);

        return new Passport(
            new UserBadge($pseudo),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Récupérer l'utilisateur authentifié
        $user = $token->getUser();

        // Vérifier les rôles de l'utilisateur
        if (in_array('Admin', $user->getRoles())) {
            // Rediriger l'administrateur vers la partie back
            return new RedirectResponse($this->urlGenerator->generate('app_back'));
        } elseif (in_array('Client', $user->getRoles())) {
            // Rediriger l'utilisateur vers la partie front
            return new RedirectResponse($this->urlGenerator->generate('app_front'));
        }
        elseif (in_array('Locateur', $user->getRoles())) {
            // Rediriger l'utilisateur vers la partie front
            return new RedirectResponse($this->urlGenerator->generate('app_front'));
        }
        elseif (in_array('Livreur', $user->getRoles())) {
            // Rediriger l'utilisateur vers la partie front
            return new RedirectResponse($this->urlGenerator->generate('app_front'));
        }

        // Rediriger vers une page par défaut si l'utilisateur n'a ni le rôle admin ni le rôle user
        return new RedirectResponse($this->urlGenerator->generate('app_front'));
    }



    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
