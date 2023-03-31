<?php

namespace App\DataFixtures;

use App\Entity\Formation;
use App\Entity\Parcours;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ParcoursFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();
        $images = ['soins1', 'soins2', 'soins3', 'soins4', 'soins5'];
        $arr = array('pre-ope' => '', 'post-ope' => '');


        for ($i=0; $i<3; $i++) {

            $object = (new Parcours())
                ->setTitle($faker->word)
                ->setDescription($faker->sentence)
                ->setUserId($faker->randomElement($users))
                ->setImage($faker->randomElement($images))
                ->setChronologie($arr)
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }

}