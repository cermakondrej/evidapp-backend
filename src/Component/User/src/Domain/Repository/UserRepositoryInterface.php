<?php
declare(strict_types=1);

namespace EvidApp\User\Domain\Repository;


use EvidApp\User\Domain\Entity\User;
use EvidApp\User\Domain\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function getOneByUuid(UserId $userId): User;

    public function findOneByUuid(UserId $userId): ?User;

    public function findOneByUsername(string $username): ?User;

    public function save(User $user): void;
}