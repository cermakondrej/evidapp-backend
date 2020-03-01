<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Query\FindAll;

use EvidApp\Shared\Infrastructure\Bus\QueryInterface;

class FindAllQuery implements QueryInterface
{
    public int $page;
    public int $limit;

    public function __construct(int $page = 1, int $limit = 50)
    {
        $this->page = $page;
        $this->limit = $limit;
    }
}