<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Query\FindByEmail;

use EvidApp\Shared\Application\Query\Item;
use EvidApp\Shared\Application\Query\QueryHandlerInterface;
use EvidApp\User\Infrastructure\Query\Repository\DatabaseUserReadRepository;
use EvidApp\User\Infrastructure\Query\Projections\UserView;


class FindByEmailHandler implements QueryHandlerInterface
{

    /** @var DatabaseUserReadRepository */
    private $repository;

    public function __construct(DatabaseUserReadRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindByEmailQuery $query): Item
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByEmail($query->email);

        return new Item($userView);
    }
}
