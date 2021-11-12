<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Http\DTO\User\CreateUserRequest;
use App\Messenger\Message\User\RegisterUserMessage;
use App\Repository\User\Doctrine\DoctrineUserRepository;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserService
{
    public function __construct(
        private DoctrineUserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private MessageBusInterface $bus
    )
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

        $this->bus->dispatch(new RegisterUserMessage($user->getName(), $user->getEmail(), $user->getActivationCode()));
        return $user;
    }


}