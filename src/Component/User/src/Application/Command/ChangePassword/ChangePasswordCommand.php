<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Command\ChangePassword;

use EvidApp\Shared\Infrastructure\Bus\CommandInterface;
use EvidApp\User\Domain\ValueObject\Auth\HashedPassword;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ChangePasswordCommand implements CommandInterface
{
    public UuidInterface $userUuid;
    public HashedPassword $password;

    public function __construct(string $userUuid, string $plainPassword)
    {
        $this->userUuid = Uuid::fromString($userUuid);
        $this->password = HashedPassword::encode($plainPassword);
    }
}
