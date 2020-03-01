<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Command\SignUp;

use EvidApp\User\Domain\ValueObject\Auth\Credentials;
use EvidApp\User\Domain\ValueObject\Auth\HashedPassword;
use EvidApp\User\Domain\ValueObject\Email;
use EvidApp\Shared\Infrastructure\Bus\CommandInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class SignUpCommand implements CommandInterface
{
    public UuidInterface $uuid;
    public Credentials $credentials;

    public function __construct(string $uuid, string $email, string $plainPassword)
    {
        $this->uuid = Uuid::fromString($uuid);
        $this->credentials = new Credentials(Email::fromString($email), HashedPassword::encode($plainPassword));
    }
}