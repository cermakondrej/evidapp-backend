<?php

declare(strict_types=1);

namespace App\Factory\ValueObject;

use App\ValueObject\Shift;
use DateTime;

class ShiftFactory
{

    public function create(array $shift): Shift
    {
        return new Shift(
            $shift['day'],
            new DateTime($shift['workStart']),
            new DateTime($shift['workEnd']),
        new DateTime($shift['breakStart']) ?? null,
            new DateTime($shift['breakEnd']) ?? null,
        );
    }
}