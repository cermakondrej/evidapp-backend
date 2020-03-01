<?php

declare(strict_types=1);

namespace EvidApp\Shared\Application\Query;

use EvidApp\Shared\Application\Query\Exception\NotFoundException;

class Collection
{
    public int $page;
    public int $limit;
    public int $total;

    /** @var Item[] */
    public array $data;

    public function __construct(int $page, int $limit, int $total, array $data)
    {
        $this->exists($page, $limit, $total);
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $total;
        $this->data = $data;
    }

    private function exists(int $page, int $limit, int $total): void
    {
        if (($limit * ($page - 1)) >= $total) {
            throw new NotFoundException();
        }
    }
}
