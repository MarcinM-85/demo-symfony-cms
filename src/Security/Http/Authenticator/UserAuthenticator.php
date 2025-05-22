<?php
namespace App\Security\Http\Authenticator;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
//use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public function __construct( private UserRepository $userRepository, private UrlGeneratorInterface $urlGenerator, private EntityManagerInterface $entityManager, private TokenStorageInterface $tokenStorage, private RequestStack $requestStack) {}

    protected function getLoginUrl(Request $request): string{
        return $this->urlGenerator->generate('admin_auth');
    }

    protected function getCheckUrl(Request $request): string{
        return $this->urlGenerator->generate('admin_2fa');
    }

    public function supports(Request $request): bool {
        return $request->isMethod('POST') && in_array($request->getBaseUrl().$request->getPathInfo(), [$this->getLoginUrl($request)]);
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
            return parent::start($request, $authException);
    }
    
    public function authenticate(Request $request): Passport
    {
            $username = $request->request->get('_username');
            $password = $request->request->get('_password');

            if (empty($username) || empty($password)) {
                throw new AuthenticationException('Username or password is missing.');
            }

            $passport = new Passport(
                new UserBadge($username, function($userIdentifier){ 
                    return $this->userRepository->findOneBy(['username' => $userIdentifier]);
                }),
                new PasswordCredentials($password)
            );
            return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
            $targetPath = $this->getTargetPath($request->getSession(), 'admin');
            if ($targetPath) {
                return new RedirectResponse($targetPath);
            }

            return new RedirectResponse( $this->urlGenerator->generate('admin_news_list') );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
            return parent::onAuthenticationFailure($request, $exception);
    }
}