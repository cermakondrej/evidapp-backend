<?php

declare(strict_types=1);

namespace App\Factory\ValueObject;

use App\ValueObject\Month;
use DateTime;

class MonthFactory
{
    /** @var DayInMonthFactory */
    private $dayInMonthFactory;

    public function __construct(DayInMonthFactory $dayInMonthFactory)
    {
        $this->dayInMonthFactory = $dayInMonthFactory;
    }


    public function create(int $month, int $year): Month
    {
        $start_date = new DateTime("01-{$month}-{$year}");
        $end_date = new DateTime("01-{$month}-{$year}");
        $end_date->modify("+1 month");
        $daysInMonth = [];
        $numberOfWorkingDays = 0;

        for ($cnt = 0; $start_date < $end_date; $cnt++) {
            $day = $this->dayInMonthFactory->create($cnt+1, $month, $year);
            if (!$day->isWeekend()) {
                $numberOfWorkingDays++;
            }
            $daysInMonth[]=$day;
            $start_date->modify('+1 day');
        }

        return new Month($numberOfWorkingDays, $daysInMonth);
    }
}
