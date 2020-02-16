<?php

declare(strict_types=1);

namespace EvidApp\Shared\Application\Query\Event\GetEvents;

use EvidApp\Shared\Infrastructure\Bus\QueryInterface;

class GetEventsQuery implements QueryInterface
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