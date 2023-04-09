<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('rick@test.nl');
        $user->setFirstName('Rick');
        $user->setLastName('in t Veld');
        $user->setPassword('Test123!');
        $user->setEnabled(true);
        $user->setCreated();
        $user->setUpdated();

        $manager->persist($user);
        $manager->flush();
    }
}
