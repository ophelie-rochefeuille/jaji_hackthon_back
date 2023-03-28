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

        $object = (new Soignant())
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setCategory($categories[0])
            ->setDoctolibUrl($faker->url)
            ->setNumNational($faker->numberBetween(1000000000, 9999999999))
        ;
        $manager->persist($object);

        $object = (new Soignant())
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setCategory($categories[1])
            ->setDoctolibUrl($faker->url)
            ->setNumNational($faker->numberBetween(1000000000, 9999999999))
        ;
        $manager->persist($object);

        $object = (new Soignant())
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setCategory($categories[2])
            ->setDoctolibUrl($faker->url)
            ->setNumNational($faker->numberBetween(1000000000, 9999999999))
        ;
        $manager->persist($object);

        $object = (new Soignant())
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setCategory($categories[3])
            ->setDoctolibUrl($faker->url)
            ->setNumNational($faker->numberBetween(1000000000, 9999999999))
        ;
        $manager->persist($object);

        $object = (new Soignant())
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setCategory($categories[4])
            ->setDoctolibUrl($faker->url)
            ->setNumNational($faker->numberBetween(1000000000, 9999999999))
        ;
        $manager->persist($object);

        $object = (new Soignant())
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setCategory($categories[5])
            ->setDoctolibUrl($faker->url)
            ->setNumNational($faker->numberBetween(1000000000, 9999999999))
        ;
        $manager->persist($object);

        $manager->flush();
    }
}