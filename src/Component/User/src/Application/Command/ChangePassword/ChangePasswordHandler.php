<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Command\ChangePassword;

use EvidApp\Shared\Application\Command\CommandHandlerInterface;
use EvidApp\User\Domain\Repository\UserRepositoryInterface;

class ChangePasswordHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(ChangePasswordCommand $command): void
    {
        $user = $this->userRepository->get($command->userUuid);

        $user->changePassword($command->password);

        $this->userRepository->store($user);
    }
}
