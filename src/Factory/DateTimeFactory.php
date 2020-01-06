<?php

declare(strict_types=1);

namespace App\Factory;

use DateTimeInterface;
use DateTimeImmutable;

class DateTimeFactory
{
    public function create(string $dateTime): DateTimeInterface
    {
        return new DateTimeImmutable($dateTime);
    }

    public function createOrNull(?string $dateTime): ?DateTimeInterface
    {
        if ($dateTime === null) {
            return null;
        }

        return new DateTimeImmutable($dateTime);
    }
}