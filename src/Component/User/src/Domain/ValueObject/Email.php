<?php

declare(strict_types=1);

namespace EvidApp\User\Domain\ValueObject;

use Assert\Assertion;

class Email
{
    private string $email;

    private function __construct()
    {
    }

    public static function fromString(string $email): self
    {
        Assertion::email($email, 'Not a valid email');

        $mail = new self();

        $mail->email = $email;

        return $mail;
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