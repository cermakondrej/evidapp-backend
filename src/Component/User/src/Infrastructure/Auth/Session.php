<?php

declare(strict_types=1);

namespace EvidApp\User\Infrastructure\Auth;

use EvidApp\User\Domain\Exception\InvalidCredentialsException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class Session
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function get(): array
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            throw new InvalidCredentialsException();
        }

        $user = $token->getUser();

        if (!$user instanceof Auth) {
            throw new InvalidCredentialsException();
        }

        return [
            'uuid' => $user->uuid(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ];
    }

    public function sameByUuid(string $uuid): bool
    {
        return $this->get()['uuid']->toString() === $uuid;
    }
}
