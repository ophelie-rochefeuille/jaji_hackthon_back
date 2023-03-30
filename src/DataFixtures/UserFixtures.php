<?php

namespace App\DataFixtures;

use App\Entity\Parcours;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $parcours = $manager->getRepository(Parcours::class)->findAll();
        // PWD = test
        $pwd = '$2y$13$wiWVplNfdpwyWjWFdTtY..TQvVVHDVkv/PEUtf7dSlvmC2KiqlJHq';

        $object = (new User())
            ->setEmail('admin@user.fr')
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setPassword($pwd)
            ->setCookie(true)
            ->setPhotoProfil('default_profile_picture.png')
            ->addParcour($faker->randomElement($parcours))
            ->setRoles(["ROLE_ADMIN"])
        ;
        $manager->persist($object);

        $object = (new User())
            ->setEmail('user@user.fr')
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setPassword($pwd)
            ->setCookie($faker->boolean())
            ->addParcour($faker->randomElement($parcours))
            ->setRoles(["ROLE_USER"])
        ;
        $manager->persist($object);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ParcoursFixtures::class
        ];
    }
}