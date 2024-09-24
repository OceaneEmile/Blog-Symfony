<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Get all articles
        $articles = $manager->getRepository(Article::class)->findAll();

        foreach ($articles as $article) {
            for ($i = 1; $i <= 5; $i++) {
                $comment = new Comment();
                $comment->setContent($faker->paragraph);
                $comment->setEmail($faker->email);
                $comment->setCreatedAt($faker->dateTimeThisYear);
                $comment->setAuthor($faker->name);
                $comment->setArticle($article);
                $manager->persist($comment);
            }
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
