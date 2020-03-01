<?php

declare(strict_types=1);

namespace EvidApp\User\Infrastructure\Query;


use EvidApp\User\Domain\Event\UserEmailChanged;
use EvidApp\User\Domain\Event\UserPasswordChanged;
use EvidApp\User\Domain\Event\UserWasCreated;
use EvidApp\User\Infrastructure\Query\Repository\DatabaseUserReadRepository;
use EvidApp\User\Infrastructure\Query\Projections\UserView;
use Broadway\ReadModel\Projector;

class UserProjectionFactory extends Projector
{
    private DatabaseUserReadRepository $repository;


    public function __construct(DatabaseUserReadRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function applyUserWasCreated(UserWasCreated $userWasCreated): void
    {
        $userReadModel = UserView::fromSerializable($userWasCreated);

        $this->repository->add($userReadModel);
    }

    protected function applyUserEmailChanged(UserEmailChanged $emailChanged): void
    {
        /** @var UserView $userReadModel */
        $userReadModel = $this->repository->oneByUuid($emailChanged->uuid);

        $userReadModel->changeEmail($emailChanged->email);
        $userReadModel->changeUpdatedAt($emailChanged->updatedAt);

        $this->repository->apply();
    }

    protected function applyUserPasswordChanged(UserPasswordChanged $passwordChanged): void
    {
        /** @var UserView $userReadModel */
        $userReadModel = $this->repository->oneByUuid($passwordChanged->uuid);

        $userReadModel->changePassword($passwordChanged->password);
        $userReadModel->changeUpdatedAt($passwordChanged->updatedAt);

        $this->repository->apply();
    }
}