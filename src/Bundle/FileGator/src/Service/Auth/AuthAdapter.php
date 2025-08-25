<?php
namespace App\Bundle\FileGator\Service\Auth;

use App\Bundle\FileGator\Model\UserInterface;
use Filegator\Services\Auth\AuthInterface;
use Filegator\Services\Auth\User as FilegatorUser;
//use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Filegator\Services\Session\SessionStorageInterface as Session;
//use Doctrine\ORM\EntityManagerInterface;
use Filegator\Services\Service;
//use App\Entity\User as AppUser;
use Filegator\Services\Auth\UsersCollection;
//use Symfony\Bundle\SecurityBundle\Security;
//use Filegator\Services\Security\Security;
use Filegator\Container\Container;
//use Symfony\Component\Security\Core\User\UserInterface;

class AuthAdapter implements Service, AuthInterface
{
    protected ?UserInterface $symfonyUser;
    protected bool $privateRepos = false;

    public function __construct(Container $container)
    {
        $this->symfonyUser = is_object($container->get(UserInterface::class)) ? $container->get(UserInterface::class) : null;
    }

    public function init(array $config = [])
    {
        $this->privateRepos = isset($config['private_repos']) ? (bool)$config['private_repos'] : false;
    }

    public function user(): ?FilegatorUser
    {
        if(is_null($this->symfonyUser))
            return null;

        return $this->transformUser($this->symfonyUser);
    }

    public function transformUser($symfonyUser): FilegatorUser
    {
        $user = new FilegatorUser();
        $user->setUsername( $symfonyUser->getFileGatorUsername() );
        $user->setName( $symfonyUser->getFileGatorName() );
        $user->setRole( $symfonyUser->getFileGatorRole() );
        $user->setPermissions( $symfonyUser->getFileGatorPermissions() );
        
        // private repositories for each user?
        if ($user->isUser()) {
            $user->setHomedir( $symfonyUser->getFileGatorHomedir($this->privateRepos) );
        } else {
            $user->setHomedir( $symfonyUser->getFileGatorHomedir(false) );
        }

        return $user;
    }

    public function authenticate($username, $password): bool
    {
        // Logowanie odbywa się tylko w Symfony, więc tu zwracamy false lub throw
        return false;
    }

    public function forget()
    {
        // Brak wylogowania FileGatora - Symfony zarządza sesją
    }

    public function find($username): ?FilegatorUser
    {
        return null;
    }

    public function store(FilegatorUser $user)
    {
        return null;
//        throw new \LogicException('User management only in Symfony panel.');
    }

    public function update($username, FilegatorUser $user, $password = ''): FilegatorUser
    {
        return new FilegatorUser();
//        throw new \LogicException('User management only in Symfony panel.');
    }

    public function add(FilegatorUser $user, $password): FilegatorUser
    {
        return new FilegatorUser();
//        throw new \LogicException('User management only in Symfony panel.');
    }

    public function delete(FilegatorUser $user)
    {
        return null; // not used
//        throw new \LogicException('User management only in Symfony panel.');
    }

    public function getGuest(): FilegatorUser
    {
        $guest = new FilegatorUser();

        $guest->setUsername('guest');
        $guest->setName('Guest');
        $guest->setRole('user');
        $guest->setHomedir('/');
        $guest->setPermissions(['read']);

        return $guest;
    }

    public function allUsers(): UsersCollection
    {
        return new UsersCollection(); // not used
    }
}