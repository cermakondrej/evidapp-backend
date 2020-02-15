<?php

declare(strict_types=1);

namespace EvidApp\Security\Domain\ValueObject;


interface EncodedPasswordInterface
{
    public function __construct(string $plainPassword);

    public function matchHash(string $hash): bool;

    public function __toString(): string;
}