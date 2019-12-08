<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public const TSCHP_COMPANY_REFERENCE = 'tschp-company';

    public function load(ObjectManager $manager)
    {
        $company = new Company();
        $company->setName("TS");
        $manager->persist($company);

        $company = new Company();
        $company->setName("TSCHP");
        $manager->persist($company);
        

        $manager->flush();

        $this->addReference(self::TSCHP_COMPANY_REFERENCE, $company);
    }
}
