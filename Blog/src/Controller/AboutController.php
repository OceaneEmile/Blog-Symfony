<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class AboutController extends AbstractController
{
    // method GET for the route /about
    #[Route('api/about', name: 'app_about', methods: ['GET'])]
    public function index(): JsonResponse
    {
        // data to return in the response body to modify later
        $data = [
            'controller_name' => 'AboutController',
            'message' => 'Qui suis-je ?',
            'status' => 'success'
        ];

        return new JsonResponse($data);
    }
}
