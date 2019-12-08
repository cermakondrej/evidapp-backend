<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    /** @var UserPasswordEncoderInterface  */
    private $encoder;

    public const ADMIN_USER_REFERENCE = 'admin-user';

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setFullName('Admin user');
        $user->setEmail('admin@user.com');
        $user->setPassword($this->encoder->encodePassword($user, 'aaaaa'));
        $user->setRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);

        $manager->persist($user);

        $manager->flush();

        $this->addReference(self::ADMIN_USER_REFERENCE, $user);
    }
}
