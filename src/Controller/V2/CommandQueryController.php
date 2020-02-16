<?php

declare(strict_types=1);

namespace App\Controller\V2;

use EvidApp\Shared\Infrastructure\Bus\CommandBus;
use EvidApp\Shared\Infrastructure\Bus\CommandInterface;
use EvidApp\Shared\Infrastructure\Bus\QueryBus;
use App\Response\JsonFormatter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommandQueryController extends QueryController
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        JsonFormatter $formatter,
        UrlGeneratorInterface $router
    ) {
        parent::__construct($queryBus, $formatter, $router);
        $this->commandBus = $commandBus;
    }

    protected function exec(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }

}