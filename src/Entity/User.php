<?php

namespace App\Entity;

use App\Model\EmailAuthCodeTwoFactorInterface;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
//use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraint as Assert;

#[UniqueEntity(fields: ['email'], message: 'Nie można użyć tego adresu e-mail')]
#[UniqueEntity(fields: ['username'], message: 'Nazwa użytkownika jest zajęta.')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EmailAuthCodeTwoFactorInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    private ?string $username = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email;
    
    /**
     * @var list<string> The user roles
     */
    #[Assert\NotBlank]
    #[Assert\Json]
    #[ORM\Column(type: 'text')]
    private string $roles = '[]';

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $authCode;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['default' => null])]
    private ?\DateTimeImmutable $authCodeExpiresAt;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username . "({$this->email})";
    }

    /**
     * @see UserInterface
     *
     * @return list<array>
     */
    public function getRoles(): array
    {
        $roles = json_decode($this->roles, true) ?? [];
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<array> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = json_encode($roles);

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function isEmailAuthEnabled(): bool
    {
        return true; // This can be a persisted field to switch email code authentication on/off
    }
    
    public function getEmailAuthRecipient(): string
    {
        return $this->getEmail();
    }
    
    public function getEmailAuthCode(): string
    {
        if (null === $this->authCode) {
            throw new \LogicException('The email authentication code was not set');
        }

        return $this->authCode;
    }

    public function setEmailAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }

    public function getEmailAuthCodeExpiresAt(): ?\DateTimeImmutable
    {
            return $this->authCodeExpiresAt;
//        if( !is_null($this->authCodeExpiresAt) )
//        {
//            return new \DateTimeImmutable($this->authCodeExpiresAt);
//        }
//        return null;
    }

    public function setEmailAuthCodeExpiresAt(?\DateTimeImmutable $expiresAt): void
    {
            $this->authCodeExpiresAt = $expiresAt;
//        $this->authCodeExpiresAt = null;
//
//        if( $expiresAt instanceof DateTimeImmutable)
//            $this->authCodeExpiresAt = $expiresAt->format('Y-m-d H:i:s');
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
