<?php
namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MessageHashGenerator {

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}
    
    public function __invoke(): string
    {
        $hash = $this->hasher->hashPassword(new User(), 'haslo123');
        return $hash;
//        return 'ToJestStringAleMozeTezBycFunckjaKtoraZwracaHash';
    }
}
