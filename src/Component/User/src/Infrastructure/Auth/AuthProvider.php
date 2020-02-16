<?php

declare(strict_types=1);

namespace EvidApp\User\Infrastructure\Auth;

use EvidApp\User\Domain\ValueObject\Email;
use EvidApp\User\Infrastructure\Query\Repository\DatabaseUserReadRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AuthProvider implements UserProviderInterface
{

    /** @var DatabaseUserReadRepository */
    private $userReadRepository;

    public function __construct(DatabaseUserReadRepository $userReadRepository)
    {
        $this->userReadRepository = $userReadRepository;
    }

    public function loadUserByUsername($email): UserInterface
    {
        // @var array $user
        [$uuid, $email, $hashedPassword] = $this->userReadRepository->getCredentialsByEmail(
            Email::fromString($email)
        );

        return Auth::create($uuid, $email, $hashedPassword);
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