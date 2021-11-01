<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Http\DTO\User\CreateUserRequest;
use App\Repository\User\Doctrine\DoctrineUserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserService
{
    public function __construct(private DoctrineUserRepository $userRepository, private UserPasswordHasherInterface $passwordHasher)
    {

    }

    public function __invoke(CreateUserRequest $request): User
    {
        $user = User::createUser($request->getName(), $request->getEmail());
        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $request->getPassword()
        );

        $user->setPassword($hashedPassword);

        $this->userRepository->save($user);
        return $user;
    }


}