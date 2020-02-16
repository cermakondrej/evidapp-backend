<?php

declare(strict_types=1);

namespace EvidApp\Shared\Infrastructure\Event\Publisher;

use Broadway\Domain\DomainMessage;

interface EventPublisherInterface
{
    public function handle(DomainMessage $message): void;

    public function publish(): void;
}