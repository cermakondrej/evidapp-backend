<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Query\FindAll;

use EvidApp\Shared\Infrastructure\Bus\QueryInterface;

class FindALlQuery implements QueryInterface
{
    /** @var int */
    public $page;

    /** @var int */
    public $limit;

    public function __construct(int $page = 1, int $limit = 50)
    {
        $this->page = $page;
        $this->limit = $limit;
    }
}