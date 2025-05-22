<?php

namespace App\Security\TwoFactor\Provider\EmailAuthCode\Generator;

use App\Mailer\EmailAuthCodeMailerInterface;
use App\Model\EmailAuthCodeTwoFactorInterface;
use App\Security\TwoFactor\Provider\EmailAuthCode\Generator\CodeGeneratorInterface;
//use Scheb\TwoFactorBundle\Mailer\AuthCodeMailerInterface;
//use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Scheb\TwoFactorBundle\Model\PersisterInterface;
//use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Email\Generator\CodeGeneratorInterface;
use Psr\Clock\ClockInterface;
use function random_int;

/**
 * @final
 */
class CodeGenerator implements CodeGeneratorInterface
{
    public function __construct(
        private readonly PersisterInterface $persister,
        private readonly EmailAuthCodeMailerInterface $mailer,
        private readonly int $digits,
        private readonly ClockInterface $clock,
        private readonly ?string $expiresAfter = null
    ) {
    }

    public function generateAndSend(EmailAuthCodeTwoFactorInterface $user): void
    {
        $min = 10 ** ($this->digits - 1);
        $max = 10 ** $this->digits - 1;
        $code = $this->generateCode($min, $max);
        $user->setEmailAuthCode((string) $code);
        if (null !== $this->expiresAfter) {
            $user->setEmailAuthCodeExpiresAt($this->clock->now()->add(new \DateInterval($this->expiresAfter)));
        } else {
            $user->setEmailAuthCodeExpiresAt(null);
        }
        
        $this->persister->persist($user);
        $this->mailer->sendAuthCode($user);
    }

    public function reSend(EmailAuthCodeTwoFactorInterface $user): void
    {
        $this->mailer->sendAuthCode($user);
    }

    protected function generateCode(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
