<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Repository\DestinationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DestinationController extends AbstractController
{
    // **GET** `/api/destinations` : REtrieve all destinations.
    #[Route('/api/destinations', name: 'get_destinations', methods: ['GET'])]
    public function getAllDestinations(DestinationRepository $destinationRepo): JsonResponse
    {
        $destinations = $destinationRepo->findAll();
        return $this->json($destinations, 200, [], ['groups' => 'destination:read']);
    }

    // **GET** `/api/destinations/{id}` : Retrieve one specific destination with id.
    #[Route('/api/destinations/{id}', name: 'get_destination', methods: ['GET'])]
    public function getDestinationById(int $id, DestinationRepository $destinationRepo): JsonResponse
    {
        $destination = $destinationRepo->find($id);
        if (!$destination) {
            return $this->json(['message' => 'Destination not found'], 404);
        }
        return $this->json($destination, 200, [], ['groups' => 'destination:read']);
    }

    // **POST** `/api/destinations` : Create a new destination.
    #[Route('/api/destinations', name: 'create_destination', methods: ['POST'])]
    public function createDestination(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['description'], $data['image'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $destination = new Destination();
        $destination->setName($data['name']);
        $destination->setDescription($data['description']);
        $destination->setImage($data['image']);

        $em->persist($destination);
        $em->flush();

        return $this->json($destination, 201, [], ['groups' => 'destination:read']);
    }

    // **PUT** `/api/destinations/{id}` : Update a destination with id.
    #[Route('/api/destinations/{id}', name: 'update_destination', methods: ['PUT'])]
    public function updateDestination(int $id, Request $request, DestinationRepository $destinationRepo, EntityManagerInterface $em): JsonResponse
    {
        $destination = $destinationRepo->find($id);
        if (!$destination) {
            return $this->json(['message' => 'Destination not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $destination->setName($data['name'] ?? $destination->getName());
        $destination->setDescription($data['description'] ?? $destination->getDescription());
        $destination->setImage($data['image'] ?? $destination->getImageUrl());
       

        $em->flush();

        return $this->json($destination, 200, [], ['groups' => 'destination:read']);
    }

    // **DELETE** `/api/destinations/{id}` : Supprimer une destination existante.
    #[Route('/api/destinations/{id}', name: 'delete_destination', methods: ['DELETE'])]
    public function deleteDestination(int $id, DestinationRepository $destinationRepo, EntityManagerInterface $em): JsonResponse
    {
        $destination = $destinationRepo->find($id);
        if (!$destination) {
            return $this->json(['message' => 'Destination not found'], 404);
        }

        $em->remove($destination);
        $em->flush();

        return $this->json(['message' => 'Destination deleted successfully']);
    }
}
