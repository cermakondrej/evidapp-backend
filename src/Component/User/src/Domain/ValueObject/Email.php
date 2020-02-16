<?php

declare(strict_types=1);

namespace EvidApp\User\Domain\ValueObject;

use Assert\Assertion;

class Email
{

    /** @var string */
    private $email;

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function fromString(string $email): self
    {
        Assertion::email($email, 'Not a valid email');

        return new self($email);
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}