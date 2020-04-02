<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();

        $userAdmin->setEmail("admin@admin.es");


        $userAdmin->setPassword($this->passwordEncoder->encodePassword(
            $userAdmin,
            '1234'
        ));


        $userAdmin->setRoles((array)"ROLE_SUPER_ADMIN");

        $manager->persist($userAdmin);


        $user = new User();

        $user->setEmail("user@user.es");


        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '1234'
        ));


        $user->setRoles((array)"ROLE_USER");

        $manager->persist($user);

        $manager->flush();
    }
}

