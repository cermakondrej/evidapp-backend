<?php

declare(strict_types=1);

namespace App\Component\Security\Infrastructure\Security;


use EvidApp\Security\Domain\ValueObject\AuthUser;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Auth implements UserInterface, EncoderAwareInterface
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var AuthUser
     */
    private $authUser;

    public function __construct(string $uuid, AuthUser $authUser)
    {
        $this->uuid = $uuid;
        $this->authUser = $authUser;
    }

    public function id(): string
    {
        return $this->uuid;
    }

    public function getRoles(): array
    {
        return $this->authUser->roles();
    }

    public function getPassword(): string
    {
        return $this->authUser->password();
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->authUser->username();
    }


    public function eraseCredentials(): void
    {

    }

    public function getEncoderName(): string
    {
        return 'harsh';
    }
}