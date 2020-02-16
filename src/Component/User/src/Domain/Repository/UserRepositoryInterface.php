<?php

declare(strict_types=1);

namespace EvidApp\User\Domain\Repository;

use EvidApp\User\Domain\User;
use Ramsey\Uuid\UuidInterface;

interface UserRepositoryInterface
{
    public function get(UuidInterface $uuid): User;

    public function store(User $user): void;
}
