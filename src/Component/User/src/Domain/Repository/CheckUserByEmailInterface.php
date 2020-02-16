<?php

declare(strict_types=1);

namespace EvidApp\User\Domain\Repository;

use EvidApp\User\Domain\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

interface CheckUserByEmailInterface
{
    public function existsEmail(Email $email): ?UuidInterface;
}