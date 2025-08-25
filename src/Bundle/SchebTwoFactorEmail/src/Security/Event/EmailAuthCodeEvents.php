<?php

declare(strict_types=1);

namespace App\Bundle\SchebTwoFactorEmail\Security\Event;

/**
 * @final
 */
class EmailAuthCodeEvents
{
    /**
     * When a code was sent by the email provider.
     */
    public const SENT = 'scheb_two_factor.provider.email_auth_code.sent';

    /**
     * When a code is about to be checked by the email provider.
     */
    public const CHECK = 'scheb_two_factor.provider.email_auth_code.check';

    /**
     * When the code was deemed to be valid by the email provider.
     */
    public const VALID = 'scheb_two_factor.provider.email_auth_code.valid';

    /**
     * When the code was deemed to be invalid by the email provider.
     */
    public const INVALID = 'scheb_two_factor.provider.email_auth_code.invalid';

    public const EXPIRED = 'scheb_two_factor.provider.email_auth_code.expired';
}
