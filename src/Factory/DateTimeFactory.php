<?php

declare(strict_types=1);

namespace App\Factory;

use DateTimeInterface;
use DateTime;

class DateTimeFactory
{
    public function create(string $dateTime): DateTimeInterface
    {
        return new DateTime($dateTime);
    }

    public function createOrNull(?string $dateTime): ?DateTimeInterface
    {
        if ($dateTime === null) {
            return null;
        }

        return new DateTime($dateTime);
    }
}