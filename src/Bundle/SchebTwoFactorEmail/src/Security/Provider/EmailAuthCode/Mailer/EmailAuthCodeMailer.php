<?php

namespace App\Bundle\SchebTwoFactorEmail\Security\Provider\EmailAuthCode\Mailer;

use App\Bundle\SchebTwoFactorEmail\Model\EmailAuthCodeTwoFactorInterface;
use App\Bundle\SchebTwoFactorEmail\Security\Provider\EmailAuthCode\Mailer\EmailAuthCodeMailerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

/**
 * @final
 */
class EmailAuthCodeMailer implements EmailAuthCodeMailerInterface
{
    private Address|string|null $senderAddress = null;

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment $twig,
        private ?string $senderEmail,
        private ?string $senderName,
        private string $emailSubject = 'Kod Autoryzacyjny',
        private string $emailTemplate = 'emails/auth_code.html.twig',
    ) {
        if (null !== $senderEmail && null !== $senderName) {
            $this->senderAddress = new Address($senderEmail, $senderName);
        } elseif (null !== $senderEmail && $senderEmail) {
            $this->senderAddress = $senderEmail;
        }
    }

    public function sendAuthCode(EmailAuthCodeTwoFactorInterface $user): void
    {
        $authCode = $user->getEmailAuthCode();
        if (null === $authCode) {
            return;
        }

        $authCodeExpiresAt = $user->getEmailAuthCodeExpiresAt();
        $authCodeExpiresAtText = !is_null($authCodeExpiresAt) ? $authCodeExpiresAt->format('Y-m-d H:i:s') : 'Bezterminowo';
        
        $htmlBody = $this->twig->render($this->emailTemplate, [
            'authCode' => $authCode,
            'authCodeExpiresAtText' => $authCodeExpiresAtText,
            'authCodeExpiresAt' => $authCodeExpiresAt,
        ]);
        $message = new Email();
        $message
            ->to($user->getEmailAuthRecipient())
            ->subject( $this->emailSubject )
            ->html($htmlBody, 'UTF-8');

        if (null !== $this->senderAddress) {
            $message->from($this->senderAddress);
        }

        $this->mailer->send($message);
    }
}
