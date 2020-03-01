<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Command\SignIn;

use EvidApp\Shared\Application\Command\CommandHandlerInterface;
use EvidApp\User\Domain\Exception\InvalidCredentialsException;
use EvidApp\User\Domain\Repository\CheckUserByEmailInterface;
use EvidApp\User\Domain\Repository\UserRepositoryInterface;
use EvidApp\User\Domain\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

class SignInHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userStore;
    private CheckUserByEmailInterface $userCollection;

    public function __construct(UserRepositoryInterface $userStore, CheckUserByEmailInterface $userCollection)
    {
        $this->userStore = $userStore;
        $this->userCollection = $userCollection;
    }

    public function __invoke(SignInCommand $command): void
    {
        $uuid = $this->uuidFromEmail($command->email);

        $user = $this->userStore->get($uuid);

        $user->signIn($command->plainPassword);

        $this->userStore->store($user);
    }

    private function uuidFromEmail(Email $email): UuidInterface
    {
        $uuid = $this->userCollection->existsEmail($email);

        if (null === $uuid) {
            throw new InvalidCredentialsException();
        }

        return $uuid;
    }
}
