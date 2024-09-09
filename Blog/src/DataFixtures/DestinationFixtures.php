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

        for ($i = 1; $i <= 5; $i++) {
            $destination = new Destination();
            $destination->setName($faker->city());
            $destination->setDescription($faker->paragraph());
            // Add an image to each destination
            $imageUrl = 'https://picsum.photos/seed/voyage' . $i . '/800/600';
            $destination->setImage($imageUrl);
            $manager->persist($destination);


            // Add a reference to each destination
            $this->addReference('destination_' . $i, $destination);
        }

        $manager->flush();
    }
}
