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
        $this->createHoliday(1, 1, $manager);
        $this->createHoliday(10, 4, $manager);
        $this->createHoliday(13, 4, $manager);
        $this->createHoliday(1, 5, $manager);
        $this->createHoliday(8, 5, $manager);
        $this->createHoliday(5, 7, $manager);
        $this->createHoliday(6, 7, $manager);
        $this->createHoliday(28, 9, $manager);
        $this->createHoliday(28, 10, $manager);
        $this->createHoliday(17, 11, $manager);
        $this->createHoliday(24, 12, $manager);
        $this->createHoliday(25, 12, $manager);
        $this->createHoliday(26, 12, $manager);

        $manager->flush();
    }

    private function createHoliday(int $day, int $month, ObjectManager $manager): void
    {
        $holiday = new Holiday();
        $holiday->setDay($day);
        $holiday->setMonth($month);
        $manager->persist($holiday);
    }
}
