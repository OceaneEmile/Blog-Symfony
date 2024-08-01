<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixtures extends Fixture
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
            $manager->persist($article);
            $this->addReference('article_' . $i, $article);
        }

        $manager->flush();
    }
}
