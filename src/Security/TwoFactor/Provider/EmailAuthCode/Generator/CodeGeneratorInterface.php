<?php

namespace App\Security\TwoFactor\Provider\EmailAuthCode\Generator;

use App\Model\EmailAuthCodeTwoFactorInterface;

interface CodeGeneratorInterface
{
    /**
     * Generate a new authentication code an send it to the user.
     */
    public function generateAndSend(EmailAuthCodeTwoFactorInterface $user): void;
}