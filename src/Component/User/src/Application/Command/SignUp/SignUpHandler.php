<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Command\SignUp;

use EvidApp\Shared\Application\Command\CommandHandlerInterface;
use EvidApp\User\Domain\Repository\UserRepositoryInterface;
use EvidApp\User\Domain\Specification\UniqueEmailSpecificationInterface;
use EvidApp\User\Domain\User;

class SignUpHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->userRepository = $userRepository;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }

    public function __invoke(SignUpCommand $command): void
    {
        $user = User::create(
            $command->uuid,
            $command->credentials,
            $this->uniqueEmailSpecification
        );

        $this->userRepository->store($user);
    }
}
