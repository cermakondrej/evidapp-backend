<?php


namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Job;
use App\Entity\User;
use App\Entity\Work;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DateTimeImmutable;

class WorkFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $work = new Work();

        /** @var Job $job */
        $job = $this->getReference(JobFixtures::MANAGER_JOB_REFERENCE);

        /** @var User $employee */
        $employee = $this->getReference(UserFixtures::ADMIN_USER_REFERENCE);

        /** @var Company $company */
        $company = $this->getReference(CompanyFixtures::TSCHP_COMPANY_REFERENCE);

        $work->setJob($job);
        $work->setCompany($company);
        $work->setEmployee($employee);
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
