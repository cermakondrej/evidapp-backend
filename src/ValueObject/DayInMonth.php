<?php

declare(strict_types=1);

namespace App\ValueObject;

class DayInMonth
{
    /** @var bool */
    private $isHoliday;

    /** @var bool */
    private $isWeekend;


    public function __construct(bool $isHoliday, bool $isWeekend)
    {
        $this->isHoliday = $isHoliday;
        $this->isWeekend = $isWeekend;
    }

    public function isHoliday(): bool
    {
        return $this->isHoliday;
    }

    public function isWeekend(): bool
    {
        return $this->isWeekend;
    }



}