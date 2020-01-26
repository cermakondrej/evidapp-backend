<?php

declare(strict_types=1);

namespace App\Factory\ValueObject;

use App\Factory\DateTimeFactory;
use App\ValueObject\Shift;
use DateTimeInterface;
use DateTimeImmutable;

class ShiftFactory
{
    private const WORK_START = 'work_start';
    private const WORK_END = 'work_end';
    private const BREAK_START = 'break_start';
    private const BREAK_END = 'break_end';

    /** @var DateTimeFactory */
    private $dateTimeFactory;

    public function __construct(DateTimeFactory $dateTimeFactory)
    {
        $this->dateTimeFactory = $dateTimeFactory;
    }

    public function createMultiple(array $inputShifts): array
    {
        // TODO Refactor this please, I did this tired af with harsh deadline
        $shifts = [];
        foreach ($inputShifts as $shift) {
            $start = $this->dateTimeFactory->create($shift[self::WORK_START]);
            $end = $this->dateTimeFactory->create($shift[self::WORK_END]);
            $breakStart = $this->dateTimeFactory->createOrNull($shift[self::BREAK_START]);
            $breakEnd = $this->dateTimeFactory->createOrNull($shift[self::BREAK_END]);
            $day = (int)$start->format('d');

            if (!$this->isSameDay($start, $end)) {

                /** @var DateTimeImmutable $midnight */
                $midnight = clone($end);
                $midnight = $midnight->setTime(0, 0, 0, 0);

                if ($breakStart !== null && $breakEnd !== null) {
                    if (!$this->isSameDay($breakStart, $breakEnd)) {
                        $shifts[] = new Shift($day, $start, $midnight, $breakStart, $midnight);
                        $breakStart = $midnight;
                    } else {
                        if ($this->isSameDay($breakStart, $start)) {
                            $shifts[] = new Shift($day, $start, $midnight, $breakStart, $breakEnd);
                            $breakStart = null;
                            $breakEnd = null;
                        } else {
                            $shifts[] = new Shift($day, $start, $midnight, null, null);
                        }
                    }
                }
                $start = $midnight;
                $day++;
            }
            $shifts[] = new Shift($day, $start, $end, $breakStart, $breakEnd);
        }
        return $shifts;
    }

    private function isSameDay(DateTimeInterface $start, DateTimeInterface $end): bool
    {
        return $start->format('Y-m-d') === $end->format('Y-m-d');
    }
}
