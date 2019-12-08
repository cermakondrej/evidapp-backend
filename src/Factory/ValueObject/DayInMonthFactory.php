<?php

declare(strict_types=1);

namespace App\Factory\ValueObject;

use App\Entity\Holiday;
use App\ValueObject\DayInMonth;
use Doctrine\ORM\EntityManagerInterface;

class DayInMonthFactory
{

    /** @var EntityManagerInterface  */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(int $day, int $month, int $year): DayInMonth
    {
        $holidays = $this->entityManager->getRepository(Holiday::class)->findAll();
        $isHoliday = false;
        /** @var Holiday $holiday */
        foreach ($holidays as $holiday) {
            if ($holiday->getDay() == $day && $holiday->getMonth() == $month) {
                $isHoliday = true;
            }
        }
        return new DayInMonth($isHoliday, $this->isDayWeekend($day, $month, $year));
    }

    private function isDayWeekend(int $day, int $month, int $year): bool
    {
        return in_array(
            date("l", (int) strtotime("{$year}-{$month}-{$day}")),
            ["Saturday", "Sunday"],
            true
        );
    }
}
