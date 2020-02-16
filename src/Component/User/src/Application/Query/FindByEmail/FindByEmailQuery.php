<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Query\FindByEmail;

use EvidApp\User\Domain\ValueObject\Email;
use EvidApp\Shared\Infrastructure\Bus\QueryInterface;

class FindByEmailQuery implements QueryInterface
{
    /** @var Email */
    public $email;

    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}