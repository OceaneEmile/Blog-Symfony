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

        // Create fake themes
        $themes = [];
        for ($i = 0; $i < 5; $i++) {
            $theme = new Theme();
            $theme->setName($faker->word());
            $manager->persist($theme);
            $themes[] = $theme;
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ArticleFixtures::class,
        ];
    }
}
