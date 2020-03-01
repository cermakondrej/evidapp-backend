<?php

declare(strict_types=1);

namespace EvidApp\Shared\Infrastructure\Event\Query;

use EvidApp\Shared\Domain\Event\EventRepositoryInterface;
use EvidApp\Shared\Infrastructure\Query\Repository\ElasticRepository;
use Broadway\Domain\DomainMessage;

final class EventElasticRepository extends ElasticRepository implements EventRepositoryInterface
{
    private const INDEX = 'events';

    public function __construct(array $elasticConfig)
    {
        parent::__construct($elasticConfig, self::INDEX);
    }

    public function store(DomainMessage $message): void
    {
        $document = [
            'type' => $message->getType(),
            'payload' => $message->getPayload()->serialize(),
            'occurred_on' => $message->getRecordedOn()->toString(),
        ];

        $this->add($document);
    }
}
