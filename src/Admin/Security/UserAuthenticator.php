<?php
namespace App\Admin\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Bundle\SecurityBundle\Security;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public function __construct( private UserRepository $userRepository, private UrlGeneratorInterface $urlGenerator, private Security $security ) {}
    
    protected function getLoginUrl(Request $request): string{
        return $this->urlGenerator->generate('admin_auth');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('_email');
        $password = $request->request->get('_password');

        if (empty($email) || empty($password)) {
            throw new AuthenticationException('Username or password is missing.');
        }

        return new Passport(
            new UserBadge($email, function (string $userIdentifier): ?UserInterface {
                return $this->userRepository->findOneBy(['email' => $userIdentifier]);
            }),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $session = $request->getSession();

        // Sprawdź, czy była zapisana strona docelowa przed logowaniem
        $targetPath = $this->getTargetPath($session, 'admin');
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse( $this->urlGenerator->generate('admin_news_list') );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return parent::onAuthenticationFailure($request, $exception);
    }

    public function supports(Request $request): bool
    {
        return parent::supports($request);
    }
}