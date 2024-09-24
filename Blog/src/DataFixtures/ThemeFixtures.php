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
        $themeData = [
            [
                'name' => 'Aventure',
                'image' => '/asset/themes/adventure.jpg'
            ],
            [
                'name' => 'Culturel',
                'image' => '/asset/themes/culturel.jpg'
            ],
            [
                'name' => 'Nature',
                'image' => '/asset/themes/nature.jpg'
            ],
            [
                'name' => 'Gastronomie',
                'image' => '/asset/themes/gastronomie.jpg'
            ],
            [
                'name' => 'Détente',
                'image' => '/asset/themes/detente.jpg'
            ],
        ];
        
        foreach ($themeData as $key => $data) {
            $theme = new Theme();
            $theme->setName($data['name']);
            $theme->setImage($data['image']); 
            $manager->persist($theme);

            // AAdd a reference to each theme
            $this->addReference('theme_' . ($key + 1), $theme);
        }

        $manager->flush();
    }
}