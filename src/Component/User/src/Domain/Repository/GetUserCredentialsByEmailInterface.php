<?php

declare(strict_types=1);

namespace EvidApp\User\Domain\Repository;

use EvidApp\User\Domain\ValueObject\Email;

interface GetUserCredentialsByEmailInterface
{
    // @TODO return encapsulated array[Uuid, string $email, string $hashedPassword]
    public function getCredentialsByEmail(Email $email): array;
}
