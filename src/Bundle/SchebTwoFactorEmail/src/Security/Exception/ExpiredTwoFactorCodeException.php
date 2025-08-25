<?php

declare(strict_types=1);

namespace App\Bundle\SchebTwoFactorEmail\Security\Exception;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * @final
 */
class ExpiredTwoFactorCodeException extends BadCredentialsException
{
    public const MESSAGE = 'Two-factor authentication code expired.';
    private const MESSAGE_KEY = 'Authentication code expired.';//'code_expired';

    public function getMessageKey(): string
    {
        return self::MESSAGE_KEY;
    }
}
