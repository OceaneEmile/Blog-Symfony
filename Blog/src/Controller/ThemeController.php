<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ThemeController extends AbstractController
{
    // **GET** `/api/themes` : Retrieve all themes.
    #[Route('/api/themes', name: 'get_themes', methods: ['GET'])]
    public function getAllThemes(ThemeRepository $themeRepo): JsonResponse
    {
        // Retrieve all themes from the database using the ThemeRepository.
        $themes = $themeRepo->findAll();

        // Return the list of themes as a JSON response with HTTP status 200.
        return $this->json($themes, 200, ['Content-Type' => 'application/json'], ['groups' => 'theme:read']);
    }

    // **GET** `/api/themes/{id}` : Retrieve a specific theme by ID.
    #[Route('/api/theme/{id}', name: 'get_theme', methods: ['GET'])]
    public function getThemeById(int $id, ThemeRepository $themeRepo): JsonResponse
    {
        // Retrieve a specific theme by its ID using the ThemeRepository.
        $theme = $themeRepo->find($id);

        // If the theme is not found, return a JSON response with HTTP status 404.
        if (!$theme) {
            return $this->json(['message' => 'Theme not found'], 404);
        }

        // Return the found theme as a JSON response with HTTP status 200.
        return $this->json($theme, 200, [], ['groups' => 'theme:read']);
    }

    // **POST** `/api/themes` : Create a new theme.
    #[Route('/api/theme', name: 'create_theme', methods: ['POST'])]
    public function createTheme(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Decode the JSON data from the request body.
        $data = json_decode($request->getContent(), true);

        // Check if the required fields are present in the request data.
        if (!isset($data['name'], $data['name'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        // Create a new Theme entity and set its properties.
        $theme = new Theme();
        $theme->setName($data['name']);
        $theme->setName($data['name']);

        // Persist the new theme to the database.
        $em->persist($theme);
        $em->flush();

        // Return the newly created theme as a JSON response with HTTP status 201.
        return $this->json($theme, 201, [], ['groups' => 'theme:read']);
    }

    // **PUT** `/api/themes/{id}` : Update an existing theme.
    #[Route('/api/theme/{id}', name: 'update_theme', methods: ['PUT'])]
    public function updateTheme(int $id, Request $request, ThemeRepository $themeRepo, EntityManagerInterface $em): JsonResponse
    {
        // Retrieve the theme to be updated by its ID.
        $theme = $themeRepo->find($id);

        // If the theme is not found, return a JSON response with HTTP status 404.
        if (!$theme) {
            return $this->json(['message' => 'Theme not found'], 404);
        }

        // Decode the JSON data from the request body.
        $data = json_decode($request->getContent(), true);

        // Update the theme's properties if they are provided in the request.
        $theme->setName($data['name'] ?? $theme->getName());

        // Save the updated theme to the database.
        $em->flush();

        // Return the updated theme as a JSON response with HTTP status 200.
        return $this->json($theme, 200, [], ['groups' => 'theme:read']);
    }

    // **DELETE** `/api/themes/{id}` : Delete an existing theme.
    #[Route('/api/theme/{id}', name: 'delete_theme', methods: ['DELETE'])]
    public function deleteTheme(int $id, ThemeRepository $themeRepo, EntityManagerInterface $em): JsonResponse
    {
        // Retrieve the theme to be deleted by its ID.
        $theme = $themeRepo->find($id);

        // If the theme is not found, return a JSON response with HTTP status 404.
        if (!$theme) {
            return $this->json(['message' => 'Theme not found'], 404);
        }

        // Remove the theme from the database.
        $em->remove($theme);
        $em->flush();

        // Return a success message as a JSON response.
        return $this->json(['message' => 'Theme deleted successfully']);
    }
}
