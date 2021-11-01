<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    const PETER_NAME = 'Peter';
    const PETER_EMAIL = 'peter@api.com';
    const PETER_PASSWORD = 'peter-password';

    const FRODO_NAME = 'Frodo Bolson';
    const FRODO_EMAIL = 'frodo@api.com';
    const FRODO_PASSWORD = 'frodo-password';

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $peter = $this->createUser(self::PETER_NAME, self::PETER_EMAIL, self::PETER_PASSWORD);
        $frodo = $this->createUser(self::FRODO_NAME, self::FRODO_EMAIL, self::FRODO_PASSWORD);
        $manager->persist($peter);
        $manager->persist($frodo);
        $manager->flush();
    }

    private function createUser(string $name, string $email, string $password): User
    {
        $user = User::createUser($name, $email);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword);
        return $user;
    }
}
