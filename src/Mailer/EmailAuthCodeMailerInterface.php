<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Model\EmailAuthCodeTwoFactorInterface;

interface EmailAuthCodeMailerInterface
{
    /**
     * Send the auth code to the user via email.
     */
    public function sendAuthCode(EmailAuthCodeTwoFactorInterface $user): void;
}
