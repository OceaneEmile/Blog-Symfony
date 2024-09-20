<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use App\Repository\DestinationRepository;
use App\Repository\ThemeRepository;

class BlogController extends AbstractController
{
    #[Route('/api/home', name: 'app_home')]
    public function index(
        ArticleRepository $articleRepo, 
        DestinationRepository $destinationRepo, 
        ThemeRepository $themeRepo
    ): JsonResponse {
        //latest 3 articles
        $articles = $articleRepo->findBy([], ['createdAt' => 'DESC'], 3);
        
        // 5 destinations
        $destinations = $destinationRepo->findBy([], [], 5);

        // All theme
        $themes = $themeRepo->findAll();

        // return JsonResponse
        return $this->json(
            [
                'article' => $articles,
                'destination' => $destinations,
                'theme' => $themes
            ],
            200,
            ['Content-Type' => 'application/json'],
            ['groups' => ['article:read', 'destination:read', 'theme:read']]  // Ajout des groupes
        );
    }
}
