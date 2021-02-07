<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

use Faker;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public const USER_REFERENCE = 'user-mitsuki';
    
    
    public function load(ObjectManager $manager)
    {
        for($count = 0; $count < 10; $count++) {
            $faker = Faker\Factory::create('fr_FR');

            $user = new User();
            $user->setEmail($faker->email);
            $user->setUsername($faker->name);
            $user->setPassword("Orochimaru");
        
            // $this->addReference(self::USER_REFERENCE, $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    // GROUPE DE FIXTURES
    
    public static function getGroups(): array
    {
        return ['group1'];
    }
}