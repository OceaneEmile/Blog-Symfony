<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 10; $i++) {
        $user = new User();
        $username = 'user' . $i; // Assurez-vous que cette valeur n'est pas NULL
        $user->setUsername($username);
        $user->setEmail("manager@manager.fr");
        $user->setRoles(['ROLE_ADMIN']); // On donne le role admin a cet user
        $user->setPassword(password_hash("okokok",PASSWORD_BCRYPT));
        $manager->persist($user); // On persist
        }
        
        $manager->flush();
    }
}
