<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Command\ChangeEmail;

use EvidApp\Shared\Application\Command\CommandHandlerInterface;
use EvidApp\User\Domain\Repository\UserRepositoryInterface;
use EvidApp\User\Domain\Specification\UniqueEmailSpecificationInterface;

class ChangeEmailHandler implements CommandHandlerInterface
{

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var UniqueEmailSpecificationInterface */
    private $uniqueEmailSpecification;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->userRepository = $userRepository;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }

    public function __invoke(ChangeEmailCommand $command): void
    {
        $user = $this->userRepository->get($command->userUuid);

        $user->changeEmail($command->email, $this->uniqueEmailSpecification);

        $this->userRepository->store($user);
    }

}