<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Query\FindByUuid;

use EvidApp\Shared\Infrastructure\Bus\QueryInterface;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

class FindByUuidQuery implements QueryInterface
{
    public UuidInterface $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = Uuid::fromString($uuid);
    }
}