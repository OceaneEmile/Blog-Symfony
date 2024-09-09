<?php
namespace App\DataFixtures;

use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ThemeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 5; $i++) {
            $theme = new Theme();
            $theme->setName($faker->word());
            $manager->persist($theme);

            // AAdd a reference to each theme
            $this->addReference('theme_' . $i, $theme);
        }

        $manager->flush();
    }
}