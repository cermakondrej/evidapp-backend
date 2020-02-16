<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Command\ChangeEmail;

use EvidApp\User\Domain\ValueObject\Email;
use EvidApp\Shared\Infrastructure\Bus\CommandInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ChangeEmailCommand implements CommandInterface
{
    /** @var UuidInterface */
    public $userUuid;

    /** @var Email */
    public $email;

    public function __construct(string $userUuid, string $email)
    {
        $this->userUuid = Uuid::fromString($userUuid);
        $this->email = Email::fromString($email);
    }
}