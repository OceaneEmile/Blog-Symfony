<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UserController extends AbstractController
{
    // **GET** `/api/users` : Retrieve all users.
    #[Route('/api/users', name: 'get_users', methods: ['GET'])]
    public function getAllUsers(UserRepository $userRepo): JsonResponse
    {
        // Retrieve all users from the database using the UserRepository.
        $users = $userRepo->findAll();

        // Return the list of users as a JSON response with HTTP status 200.
        return $this->json($users, 200, [], ['groups' => 'user:read']);
    }

    // **GET** `/api/users/{id}` : Retrieve a specific user by ID.
    #[Route('/api/user/{id}', name: 'get_user', methods: ['GET'])]
    public function getUserById(int $id, UserRepository $userRepo): JsonResponse
    {
        // Retrieve a specific user by its ID using the UserRepository.
        $user = $userRepo->find($id);

        // If the user is not found, return a JSON response with HTTP status 404.
        if (!$user) {
            return $this->json(['message' => 'User not found'], 404);
        }

        // Return the found user as a JSON response with HTTP status 200.
        return $this->json($user, 200, [], ['groups' => 'user:read']);
    }

    // **POST** `/api/users` : Register a new user.
    #[Route('/api/users', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        if (!isset($data['email'], $data['password'], $data['username'])) {
            return $this->json(['error' => 'Invalid data: email, password, and username are required'], 400);
        }
    
        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
    
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
    
        $em->persist($user);
        $em->flush();
    
        return $this->json($user, 201, [], ['groups' => 'user:read']);
    }

    // **PUT** `/api/users/{id}` : Update an existing user.
    #[Route('/api/users/{id}', name: 'update_user', methods: ['PUT'])]
    public function updateUser(
        int $id, 
        Request $request, 
        UserRepository $userRepo, 
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): JsonResponse {
        // Retrieve the user to be updated by its ID.
        $user = $userRepo->find($id);

        // If the user is not found, return a JSON response with HTTP status 404.
        if (!$user) {
            return $this->json(['message' => 'User not found'], 404);
        }

        // Decode the JSON data from the request body.
        $data = json_decode($request->getContent(), true);

        // Update the user's properties if they are provided in the request.
        $user->setEmail($data['email'] ?? $user->getEmail());
        $user->setUsername($data['username'] ?? $user->getUsername());
        $user->setRoles($data['roles'] ?? $user->getRoles());
        $user->setPassword($data['password'] ?? $user->getPassword());

        
        // If password is provided, hash it before updating
        if (isset($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        // Save the updated user to the database.
        $em->flush();

        // Return the updated user as a JSON response with HTTP status 200.
        return $this->json($user, 200, [], ['groups' => 'user:read']);
    }


    // **DELETE** `/api/users/{id}` : Delete an existing user.
    #[Route('/api/users/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(int $id, UserRepository $userRepo, EntityManagerInterface $em): JsonResponse
    {
        // Retrieve the user to be deleted by its ID.
        $user = $userRepo->find($id);

        // If the user is not found, return a JSON response with HTTP status 404.
        if (!$user) {
            return $this->json(['message' => 'User not found'], 404);
        }

        // Remove the user from the database.
        $em->remove($user);
        $em->flush();

        // Return a success message as a JSON response.
        return $this->json(['message' => 'User deleted successfully']);
    }

}
