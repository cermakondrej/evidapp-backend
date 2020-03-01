<?php

declare(strict_types=1);

namespace App\Controller\V2;

use EvidApp\Shared\Infrastructure\Bus\CommandBus;
use EvidApp\Shared\Infrastructure\Bus\CommandInterface;

abstract class CommandController
{

    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    protected function exec(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }
}
