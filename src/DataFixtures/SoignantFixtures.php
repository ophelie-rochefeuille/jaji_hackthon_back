<?php

namespace App\DataFixtures;

use App\Entity\Soignant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SoignantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $categories = ['Médecin', 'Pharmacien', 'Kiné', 'Infirmier', 'Osthéo', 'Psychothérapeute'];
        $images = ['soignant1','soignant2', 'soignant3', 'soignant4', 'soignant5'];

        for($i=0;$i<7; $i++)
        {
            $object = (new Soignant())
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setCategory($categories[$i])
                ->setDoctolibUrl($faker->url)
                ->setNumNational($faker->numberBetween(1000000000, 9999999999))
                ->setImage($faker->randomElement($images))
            ;
            $manager->persist($object);
        }
        $manager->flush();
    }
}