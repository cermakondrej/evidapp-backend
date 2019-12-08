<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class JobFixtures extends Fixture
{
    public const MANAGER_JOB_REFERENCE = 'manager-job';

    public function load(ObjectManager $manager)
    {
        $job = new Job();
        $job->setName("Uklizecka");
        $manager->persist($job);

        $job = new Job();
        $job->setName("Tlumocnik");
        $manager->persist($job);

        $job = new Job();
        $job->setName("Reditel");
        $manager->persist($job);

        $manager->flush();

        $this->addReference(self::MANAGER_JOB_REFERENCE, $job);
    }
}
