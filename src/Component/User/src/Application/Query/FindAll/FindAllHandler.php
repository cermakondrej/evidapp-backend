<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Query\FindAll;

use EvidApp\Shared\Application\Query\Collection;
use EvidApp\Shared\Application\Query\QueryHandlerInterface;
use EvidApp\User\Infrastructure\Query\Repository\DatabaseUserReadRepository;


class FindAllHandler implements QueryHandlerInterface
{
    private DatabaseUserReadRepository $repository;

    public function __construct(DatabaseUserReadRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindAllQuery $query): Collection
    {
        $result = $this->repository->page($query->page, $query->limit);

        return new Collection($query->page, $query->limit, $result['total'], $result['data']);
    }
}