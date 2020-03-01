<?php

declare(strict_types=1);

namespace EvidApp\Shared\Infrastructure\Event\Consumer;

use EvidApp\Shared\Infrastructure\Event\Query\EventElasticRepository;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class SendEventsToElasticConsumer implements ConsumerInterface
{
    private EventElasticRepository $eventElasticRepository;

    public function __construct(EventElasticRepository $eventElasticRepository)
    {
        $this->eventElasticRepository = $eventElasticRepository;
    }

    public function execute(AMQPMessage $msg): void
    {
        $this->eventElasticRepository->store(unserialize($msg->body));
    }
}