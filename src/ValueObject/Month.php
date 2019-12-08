<?php

declare(strict_types=1);

namespace App\ValueObject;

class Month
{

    /** @var int */
    private $numberOfWorkingDays;

    /** @var DayInMonth[] */
    private $daysInMonth;


    public function __construct(int $numberOfWorkingDays, array $daysInMonth)
    {
        $this->numberOfWorkingDays = $numberOfWorkingDays;
        $this->daysInMonth = $daysInMonth;
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
