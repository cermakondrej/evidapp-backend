<?php

declare(strict_types=1);

namespace EvidApp\Shared\Application\Query\Event\GetEvents;

use EvidApp\Shared\Application\Query\Collection;
use EvidApp\Shared\Application\Query\QueryHandlerInterface;
use EvidApp\Shared\Domain\Event\EventRepositoryInterface;

class GetEventsHandler implements QueryHandlerInterface
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function __invoke(GetEventsQuery $query): Collection
    {
        $result = $this->eventRepository->page($query->page, $query->limit);

        return new Collection($query->page, $query->limit, $result['total'], $result['data']);
    }
}
