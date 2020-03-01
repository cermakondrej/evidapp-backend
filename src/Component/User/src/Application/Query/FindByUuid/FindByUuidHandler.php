<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Query\FindByUuid;

use EvidApp\Shared\Application\Query\Item;
use EvidApp\Shared\Application\Query\QueryHandlerInterface;
use EvidApp\User\Infrastructure\Query\Repository\DatabaseUserReadRepository;
use EvidApp\User\Infrastructure\Query\Projections\UserView;


class FindByUuidHandler implements QueryHandlerInterface
{

    /** @var DatabaseUserReadRepository */
    private DatabaseUserReadRepository $repository;

    public function __construct(DatabaseUserReadRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindByUuidQuery $query): Item
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($query->uuid);

        return new Item($userView);
    }
}
