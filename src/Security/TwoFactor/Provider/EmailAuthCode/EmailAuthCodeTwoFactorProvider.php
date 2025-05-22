<?php
namespace App\Security\TwoFactor\EmailAuthCode\Provider;

use App\Model\EmailAuthCodeTwoFactorInterface;
use App\Security\Authentication\Exception\ExpiredTwoFactorCodeException;
use App\Security\TwoFactor\Event\EmailAuthCodeEvents;
use App\Security\TwoFactor\Provider\EmailAuthCode\Generator\CodeGeneratorInterface;
//use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
//use Scheb\TwoFactorBundle\Security\TwoFactor\Event\EmailCodeEvents;
use Scheb\TwoFactorBundle\Security\TwoFactor\Event\TwoFactorCodeEvent;
use Scheb\TwoFactorBundle\Security\TwoFactor\AuthenticationContextInterface;
//use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Email\EmailTwoFactorProvider;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorProviderInterface;
//use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Email\Generator\CodeGeneratorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorFormRendererInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Psr\Clock\ClockInterface;

class EmailAuthCodeTwoFactorProvider implements TwoFactorProviderInterface
{
 
    public function __construct(
        private readonly CodeGeneratorInterface $codeGenerator,
        private readonly TwoFactorFormRendererInterface $formRenderer,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly ClockInterface $clock
    ) {
    }
    
    public function beginAuthentication(AuthenticationContextInterface $context): bool
    {
        // Check if user can do email authentication
        $user = $context->getUser();

        return $user instanceof EmailAuthCodeTwoFactorInterface && $user->isEmailAuthEnabled();
    }
    
    public function prepareAuthentication(object $user): void
    {
        if (!($user instanceof EmailAuthCodeTwoFactorInterface)) {
            return;
        }

        $this->codeGenerator->generateAndSend($user);

        $event = new TwoFactorCodeEvent($user, $user->getEmailAuthCode() ?? '');
        $this->eventDispatcher->dispatch($event, EmailAuthCodeEvents::SENT);
    }
    
    public function validateAuthenticationCode(object $user, string $authenticationCode): bool
    {
        if (!($user instanceof EmailAuthCodeTwoFactorInterface)) {
            return false;
        }

        $event = new TwoFactorCodeEvent($user, $authenticationCode);
        $this->eventDispatcher->dispatch($event, EmailAuthCodeEvents::CHECK);

        $expiresAt = $user->getEmailAuthCodeExpiresAt();
        if (null !== $expiresAt && $this->clock->now()->getTimestamp() >= $expiresAt->getTimestamp()) {
            $this->eventDispatcher->dispatch($event, EmailAuthCodeEvents::EXPIRED);

            throw new ExpiredTwoFactorCodeException(ExpiredTwoFactorCodeException::MESSAGE);
//            $this->codeGenerator->generateAndSend($user);
//            return false;
        }
        // Strip any user added spaces
        $authenticationCode = str_replace(' ', '', $authenticationCode);
        $isValid = $user->getEmailAuthCode() === $authenticationCode;
        $this->eventDispatcher->dispatch($event, $isValid ? EmailAuthCodeEvents::VALID : EmailAuthCodeEvents::INVALID);

        return $isValid;
    }

    public function getFormRenderer(): TwoFactorFormRendererInterface
    {
        return $this->formRenderer;
    }
}