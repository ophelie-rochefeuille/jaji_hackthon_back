<?php

namespace App\DataFixtures;

use App\Entity\Formation;
use App\Entity\Parcours;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FormationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $parcours = $manager->getRepository(Parcours::class)->findAll();
        $images = ['formation1.jpg', 'formation2.jpg','formation3.jpg','formation4.jpg','default_formation_image.jpg'];

        for ($i=0; $i<5; $i++) {
            $object = (new Formation())
                ->setTitle($faker->word)
                ->setDescription($faker->sentence)
                ->setParcours($faker->randomElement($parcours))
                ->setUrl($faker->url)
                ->setImage($faker->randomElement($images))
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ParcoursFixtures::class
        ];
    }
}