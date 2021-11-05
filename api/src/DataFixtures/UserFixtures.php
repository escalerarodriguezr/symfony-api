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

    const NOT_EMAIL_CONFIRMED_NAME = 'Melkor';
    const NOT_EMAIL_CONFIRMED_EMAIL = 'melkor@api.com';
    const NOT_EMAIL_CONFIRMED_PASSWORD = 'melkor-password';

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $peter = $this->createUser(self::PETER_NAME, self::PETER_EMAIL, self::PETER_PASSWORD);
        $frodo = $this->createUser(self::FRODO_NAME, self::FRODO_EMAIL, self::FRODO_PASSWORD);
        $melkor = $this->createNotEmailConfirmedUser(self::NOT_EMAIL_CONFIRMED_NAME, self::NOT_EMAIL_CONFIRMED_EMAIL, self::NOT_EMAIL_CONFIRMED_PASSWORD);
        $manager->persist($peter);
        $manager->persist($frodo);
        $manager->persist($melkor);
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
        $user->setConfirmedEmail(true);
        $user->setActive(true);
        return $user;
    }

    private function createNotEmailConfirmedUser(string $name, string $email, string $password): User
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
