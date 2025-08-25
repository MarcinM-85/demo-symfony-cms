<?php

declare(strict_types=1);

namespace App\Bundle\SchebTwoFactorEmail\Security\Provider\EmailAuthCode\Mailer;

use App\Bundle\SchebTwoFactorEmail\Model\EmailAuthCodeTwoFactorInterface;

interface EmailAuthCodeMailerInterface
{
    /**
     * Send the auth code to the user via email.
     */
    public function sendAuthCode(EmailAuthCodeTwoFactorInterface $user): void;
}
