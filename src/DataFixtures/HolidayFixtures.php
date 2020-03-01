<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Holiday;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class HolidayFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        for ($i = 2019; $i < 2026; $i++) {
            $this->createHoliday(1, 1, $i, $manager);
            $this->createHoliday(1, 5, $i, $manager);
            $this->createHoliday(8, 5, $i, $manager);
            $this->createHoliday(5, 7, $i, $manager);
            $this->createHoliday(6, 7, $i, $manager);
            $this->createHoliday(28, 9, $i, $manager);
            $this->createHoliday(28, 10, $i, $manager);
            $this->createHoliday(17, 11, $i, $manager);
            $this->createHoliday(24, 12, $i, $manager);
            $this->createHoliday(25, 12, $i, $manager);
            $this->createHoliday(26, 12, $i, $manager);
        }
        $this->createHoliday(19, 4, 2019, $manager);
        $this->createHoliday(22, 4, 2019, $manager);
        $this->createHoliday(10, 4, 2020, $manager);
        $this->createHoliday(13, 4, 2020, $manager);
        $this->createHoliday(2, 4, 2021, $manager);
        $this->createHoliday(5, 4, 2021, $manager);
        $this->createHoliday(15, 4, 2022, $manager);
        $this->createHoliday(18, 4, 2022, $manager);
        $this->createHoliday(7, 4, 2023, $manager);
        $this->createHoliday(10, 4, 2023, $manager);
        $this->createHoliday(29, 3, 2024, $manager);
        $this->createHoliday(1, 4, 2024, $manager);
        $this->createHoliday(18, 4, 2025, $manager);
        $this->createHoliday(21, 4, 2025, $manager);

        $manager->flush();
    }

    private function createHoliday(int $day, int $month, int $year, ObjectManager $manager): void
    {
        $holiday = new Holiday();
        $holiday->setDay($day);
        $holiday->setMonth($month);
        $holiday->setYear($year);
        $manager->persist($holiday);
    }
}
