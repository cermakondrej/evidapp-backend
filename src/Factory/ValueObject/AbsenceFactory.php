<?php

declare(strict_types=1);

namespace App\Factory\ValueObject;

use App\ValueObject\Absence;

class AbsenceFactory
{

    public function create(array $absence): Absence
    {
        return new Absence($absence['day'], $absence['value']);
    }
}
