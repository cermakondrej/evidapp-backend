<?php

declare(strict_types=1);

namespace EvidApp\Shared\Domain\Event;

interface EventRepositoryInterface
{
    public function page(int $page = 1, int $limit = 50): array;
}
