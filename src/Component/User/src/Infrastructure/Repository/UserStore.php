<?php

declare(strict_types=1);

namespace EvidApp\User\Infrastructure\Repository;


use EvidApp\User\Domain\Repository\UserRepositoryInterface;
use EvidApp\User\Domain\User;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Ramsey\Uuid\UuidInterface;

final class UserStore extends EventSourcingRepository implements UserRepositoryInterface
{

    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            User::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function store(User $user): void
    {
        $this->save($user);
    }

    public function get(UuidInterface $uuid): User
    {
        /** @var User $user */
        $user = $this->load($uuid->toString());

        return $user;
    }
}