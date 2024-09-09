<?php

namespace App\DataFixtures;

use App\Entity\Destination;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DestinationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        //Create fake destinations
        for ($i = 0; $i < 20; $i++) {
            $destination = new Destination();
            $destination->setName($faker->country());
            $destination->setImage($faker->imageUrl(640, 480, 'country', true)); 
            $destination->setDescription($faker->paragraph());
            $manager->persist($destination);
        }
        $manager->flush();
    }

}
