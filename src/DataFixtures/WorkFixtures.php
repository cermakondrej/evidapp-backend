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
use DateTimeInterface;

class WorkFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $manager->persist($this->createWork(1, new DateTimeImmutable('2000-01-01T0:00')));
        $manager->persist($this->createWork(0.9, new DateTimeImmutable('2000-01-01T0:00')));
        $manager->persist($this->createWork(0.8, new DateTimeImmutable('2000-01-01T0:00')));
        $manager->persist($this->createWork(0.7, new DateTimeImmutable('2000-01-01T0:00')));
        $manager->persist($this->createWork(0.6, new DateTimeImmutable('2000-01-01T0:00')));
        $manager->persist($this->createWork(0.5, new DateTimeImmutable('2000-01-01T0:00')));
        $manager->persist($this->createWork(0.4, new DateTimeImmutable('2000-01-01T0:00')));
        $manager->persist($this->createWork(0.3, new DateTimeImmutable('2000-01-01T0:00')));
        $manager->persist($this->createWork(0.2, new DateTimeImmutable('2000-01-01T0:00')));
        $manager->persist($this->createWork(0.15, new DateTimeImmutable('2000-01-01T0:00')));
        $manager->persist($this->createWork(0.1, new DateTimeImmutable('2000-01-01T0:00')));

        $manager->flush();
    }

    private function createWork(float $workload, DateTimeInterface $start): Work
    {
        /** @var Job $job */
        $job = $this->getReference(JobFixtures::MANAGER_JOB_REFERENCE);

        /** @var User $employee */
        $employee = $this->getReference(UserFixtures::ADMIN_USER_REFERENCE);

        /** @var Company $company */
        $company = $this->getReference(CompanyFixtures::TSCHP_COMPANY_REFERENCE);

        $work = new Work();
        $work->setJob($job);
        $work->setCompany($company);
        $work->setEmployee($employee);
        $work->setWorkload($workload);
        $work->setStart($start);
        $work->setBreakStart(new DateTimeImmutable('2000-01-01T12:00'));
        $work->setBreakEnd(new DateTimeImmutable('2000-01-01T12:30'));

        return $work;
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
