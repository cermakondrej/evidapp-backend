<?php

declare(strict_types=1);

namespace App\Factory\DTO;

use App\DTO\ExportRow;
use App\ValueObject\Shift;

class ExportRowFactory
{
    public function create(Shift $shift): ExportRow
    {
        return new ExportRow(
            $shift->getDay(),
            $shift->getWorkStart(),
            $shift->getWorkEnd(),
            $shift->getBreakStart(),
            $shift->getBreakEnd()
        );
    }

    public function createEmpty(int $day)
    {
        return new ExportRow($day);
    }
}
