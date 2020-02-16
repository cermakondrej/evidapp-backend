<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Query\FindByEmail;

use EvidApp\Shared\Application\Query\Collection;
use EvidApp\Shared\Application\Query\QueryHandlerInterface;
use EvidApp\User\Infrastructure\Query\Repository\DatabaseUserReadRepository;


class FindAllHandler implements QueryHandlerInterface
{

    /** @var DatabaseUserReadRepository */
    private $repository;

    public function __construct(DatabaseUserReadRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(): Collection
    {
        $data = $this->repository->all();

        return new Collection(1, 1, 1, $data);
    }
}