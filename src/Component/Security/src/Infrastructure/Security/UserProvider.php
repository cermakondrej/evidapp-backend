<?php


namespace App\Component\Security\Infrastructure\Security;


use App\Component\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;


class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->repository->findOneByUsername($username);

        if (!$user) {

            throw new UsernameNotFoundException();
        }

        return new Auth($user->uuid()->__toString(), $user->auth());
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUsername());
    }


    public function supportsClass($class): bool
    {
        return Auth::class === $class;
    }
}