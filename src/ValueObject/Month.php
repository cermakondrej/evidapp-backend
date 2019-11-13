<?php

declare(strict_types=1);

namespace App\ValueObject;

class Month
{
    /** @var DayInMonth[] */
    private $daysInMonth;

    /** @var int */
    private $numberOfWorkingDays;


    public function __construct(array $daysInMonth, int $numberOfWorkingDays)
    {
        $this->daysInMonth = $daysInMonth;
        $this->numberOfWorkingDays = $numberOfWorkingDays;
    }

    /**
     * @return DayInMonth[]
     */
    public function getDaysInMonth(): array
    {
        return $this->daysInMonth;
    }

    public function getNumberOfWorkingDays(): int
    {
        return $this->numberOfWorkingDays;
    }




}