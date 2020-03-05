<?php

declare(strict_types=1);

namespace EvidApp\Employment\Domain\ValueObject;

use Assert\Assertion;

class Company
{
    private string $name;

    private function __construct(string $name)
    {
    }

    public static function fromString(string $name): self
    {
        Assertion::notEmpty($name, 'Not a valid name');

        return new self($name);
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}