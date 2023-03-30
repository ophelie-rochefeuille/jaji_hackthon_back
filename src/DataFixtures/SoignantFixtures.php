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

        $images = ['soignant1.jpg','soignant2.jpg','soignant3.jpg','soignant4.jpg','soignant5.jpg'];

        for($i=0;$i<7; $i++)
        {
            $c = $i%6;
            $object = (new Soignant())
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setCategory($categories[$c])
                ->setDoctolibUrl($faker->url)
                ->setNumNational($faker->numberBetween(1000000000, 9999999999))
                ->setImage($faker->randomElement($images))
            ;
            $manager->persist($object);
        }
        $manager->flush();
    }
}