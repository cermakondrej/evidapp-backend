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

    public function createEmployee(
        int $day,
        float $worked,
        \DateTimeInterface $workStart,
        \DateTimeInterface $breakStart): ExportRow
    {
        $workEnd = clone $workStart;
        $breakEnd =clone $breakStart;
        if($worked > 6)
        {
            $row = new ExportRow(
                $day,
                $workStart,
                $workEnd->add(\DateInterval::createFromDateString(($worked+0.5) * 3600 . ' seconds')),
                $breakStart,
                $breakEnd->add(\DateInterval::createFromDateString('30 minutes'))
            );

            $row->setHoursWorked(number_format($worked,2));
            $row->setDarkRow(false);
            return $row;
        }

        $row = new ExportRow(
            $day,
            $workStart,
            $workEnd->add(\DateInterval::createFromDateString(3600*$worked. ' seconds'))
        );
        $row->setDarkRow(false);
        $row->setHoursWorked(number_format($worked,2));
        return $row;
    }

    public function createEmpty(int $day)
    {
        return new ExportRow($day);
    }
}
