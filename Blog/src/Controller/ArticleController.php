<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\DestinationRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    //- **GET** `/api/articles` : Retrieve all articles.
    #[Route('/api/articles', name: 'get_articles', methods: ['GET'])]
    public function getAllArticles(ArticleRepository $articleRepo): JsonResponse
    {
        $articles = $articleRepo->findAll();
        return $this->json($articles, 200, [], ['groups' => 'article:read']);
    }
    //- **GET** `/api/articles/{id}` : Retrieve one specific article with id
    #[Route('/api/article/{id}', name: 'get_article', methods: ['GET'])]
    public function getArticleById(int $id, ArticleRepository $articleRepo): JsonResponse
    {
        $article = $articleRepo->find($id);
        // Error Alert:  If article not found, return 404
        if (!$article) {
            return $this->json(['message' => 'Article not found'], 404);
        }
        return $this->json($article, 200, [], ['groups' => 'article:read']);
    }
    //- **POST** `/api/articles` : Create a new article.
    #[Route('/api/article', name: 'create_article', methods: ['POST'])]
    public function createArticle(Request $request, EntityManagerInterface $em): JsonResponse
    {   //getcontent of the request
        // convert json string to php array ( true)
        $data = json_decode($request->getContent(), true);

        $article = new Article(); // create new article object
        $article->setTitle($data['title']);
        $article->setContent($data['content']);
         $article->setImage($data['image']);
        $article->setCreatedAt(new \DateTime());
        $article->setAuthor($data['author']);

        $em->persist($article);
        $em->flush();
        // return the article with 201 status code (created), 200 is the default status code
        return $this->json($article, 201, [], ['groups' => 'article:read']);
        if (!isset($data['title'], $data['content'], $data['image'], $data['author'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }
        
    }
    //- **PUT** `/api/articles/{id}` : Update an article with id.
    #[Route('/api/article/{id}', name: 'update_article', methods: ['PUT'])]
    public function updateArticle(int $id, Request $request, ArticleRepository $articleRepo, EntityManagerInterface $em): JsonResponse
    {
        // retrieve the article with id
        $article = $articleRepo->find($id);
        if (!$article) {
            return $this->json(['message' => 'Article not found'], 404);
        }
        //create a new article object
        $data = json_decode($request->getContent(), true);
        $article->setTitle($data['title'] ?? $article->getTitle());
        $article->setContent($data['content'] ?? $article->getContent());
        $article->setImage($data['image'] ?? $article->getImageUrl());
        $article->setCreatedAt(new \DateTime());

        //not necessary to persist the article object because it's already in the database
        $em->flush();

        return $this->json($article, 200, [], ['groups' => 'article:read']);
    }
    // - **DELETE** `/api/articles/{id}` : Delete an article with id.
    #[Route('/api/article/{id}', name: 'delete_article', methods: ['DELETE'])]
    public function deleteArticle(int $id, ArticleRepository $articleRepo, EntityManagerInterface $em): JsonResponse
    {
        $article = $articleRepo->find($id);
        if (!$article) {
            return $this->json(['message' => 'Article not found'], 404);
        }
        // remove the article from the database
        $em->remove($article);
        $em->flush();

        return $this->json(['message' => 'Article deleted successfully']);
    }
    // - **GET** `/api/articles/destination/{destinationId}` : Retrieve articles by destination. for the menu
    #[Route('/api/articles/destination/{destinationId}', name: 'get_articles_by_destination', methods: ['GET'])]
    public function getArticlesByDestination(int $destinationId, ArticleRepository $articleRepo): JsonResponse
    {
        $articles = $articleRepo->findBy(['destination' => $destinationId]);
        return $this->json($articles, 200, [], ['groups' => 'destination:show','destination:read']);
    }
    // - **GET** `/api/articles/theme/{themeId}` : Retrieve articles by theme. for the menu
    #[Route('/api/articles/theme/{themeId}', name: 'get_articles_by_theme', methods: ['GET'])]
    public function getArticlesByTheme(int $themeId, ArticleRepository $articleRepo): JsonResponse
    {
        $articles = $articleRepo->findBy(['theme' => $themeId]);
        return $this->json($articles, 200, [], ['groups' => 'theme:read','theme:article']);
    }
}

