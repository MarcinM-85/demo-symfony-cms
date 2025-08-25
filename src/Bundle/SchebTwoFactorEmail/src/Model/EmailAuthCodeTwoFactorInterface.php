<?php
declare(strict_types=1);

namespace App\Bundle\SchebTwoFactorEmail\Model;

interface EmailAuthCodeTwoFactorInterface
{
    /**
     * Return true if the user should do two-factor authentication.
     */
    public function isEmailAuthEnabled(): bool;

    /**
     * Return user email address.
     */
    public function getEmailAuthRecipient(): string;

    /**
     * Return the authentication code.
     */
    public function getEmailAuthCode(): string|null;

    /**
     * Set the authentication code.
     */
    public function setEmailAuthCode(string $authCode): void;

    /**
     * Timestamp of when the authentication code will expire.
     */
    public function getEmailAuthCodeExpiresAt(): ?\DateTimeImmutable;

    /**
     * Set the timestamp of when the authentication code will expire.
     */
    public function setEmailAuthCodeExpiresAt(?\DateTimeImmutable $expiresAt): void;
}
