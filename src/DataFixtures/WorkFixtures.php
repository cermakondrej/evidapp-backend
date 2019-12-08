<?php


namespace App\DataFixtures;

use App\Entity\Job;
use App\Entity\Work;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DateTimeImmutable;

class WorkFixtures extends Fixture implements DependentFixtureInterface
{
    /** @noinspection PhpParamsInspection */
    public function load(ObjectManager $manager)
    {
        $work = new Work();
        $work->setJob($this->getReference(JobFixtures::MANAGER_JOB_REFERENCE));
        $work->setCompany($this->getReference(CompanyFixtures::TSCHP_COMPANY_REFERENCE));
        $work->setEmployee($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $work->setWorkload(1);
        $work->setStart(new DateTimeImmutable('2000-01-01T09:00'));
        $work->setBreakStart(new DateTimeImmutable('2000-01-01T12:00'));
        $work->setBreakEnd(new DateTimeImmutable('2000-01-01T12:30'));

        $manager->persist($work);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            JobFixtures::class,
            CompanyFixtures::class,
        );
    }

}
