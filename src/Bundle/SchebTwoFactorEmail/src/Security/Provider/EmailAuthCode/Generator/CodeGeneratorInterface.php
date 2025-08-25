<?php

namespace App\Bundle\SchebTwoFactorEmail\Security\Provider\EmailAuthCode\Generator;

use App\Bundle\SchebTwoFactorEmail\Model\EmailAuthCodeTwoFactorInterface;

interface CodeGeneratorInterface
{
    /**
     * Generate a new authentication code an send it to the user.
     */
    public function generateAndSend(EmailAuthCodeTwoFactorInterface $user): void;
}