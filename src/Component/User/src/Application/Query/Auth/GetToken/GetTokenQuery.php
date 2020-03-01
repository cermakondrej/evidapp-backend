<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Query\Auth\GetToken;

use EvidApp\Shared\Infrastructure\Bus\QueryInterface;
use EvidApp\User\Domain\ValueObject\Email;

class GetTokenQuery implements QueryInterface
{
    public Email $email;

    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}
