<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UserFixtures;

use Faker;

class CommentFixtures extends Fixture /*implements FixtureGroupInterface*/
{
    public function load(ObjectManager $manager)
    {
        // $faker = Faker\Factory::create('fr_FR');

        // for($count = 0; $count < 3; $count++) {
        //     $comment = new Comment();
        //     $comment->setContent($faker->text);
        //     $comment->setCreatedAt($faker->dateTime());
        //     // $comment->setAuthor($this->getReference(UserFixtures::USER_REFERENCE)); /*ID*/
        //     // $comment->setParent(); /*ID*/

        //     $manager->persist($comment);
        // }
        
        // $manager->flush();
    }

    // public function getDependencies()
    // {
    //     return array(
    //         UserFixtures::class
    //     );
    // }

    // GROUPE DE FIXTURES

    // public static function getGroups(): array
    // {
    //     return ['group1'];
    // }
}
