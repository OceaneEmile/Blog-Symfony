<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 20; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence);
            $article->setContent($faker->paragraphs(3, true));
            $article->setCreatedAt($faker->dateTimeThisYear);
            $article->setAuthor($faker->name);

            // Generate a unique image for each article
            $imageUrl = 'https://picsum.photos/seed/voyage' . $i . '/800/600';
            $article->setImage($imageUrl);

            //Add reference to each article destination and theme ( relation ManyToOne )
            $destination = $this->getReference('destination_' . rand(1, 5));
            $theme = $this->getReference('theme_' . rand(1, 5));

            $article->setDestination($destination);
            $article->setTheme($theme);

            $manager->persist($article);
        }

        $manager->flush();
    }
    // To loads fixtures in a specific order, you can implement the getDependencies method
    public function getDependencies()
    {
        return [
            DestinationFixtures::class,
            ThemeFixtures::class,
        ];
    }
}
