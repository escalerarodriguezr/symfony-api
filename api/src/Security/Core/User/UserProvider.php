<?php

declare(strict_types=1);

namespace App\Security\Core\User;

use App\Entity\User;

use App\Repository\User\Doctrine\DoctrineUserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    private DoctrineUserRepository $userRepository;

    public function __construct(DoctrineUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        try {
            return $this->userRepository->findOneByEmailOrFail($username);
        } catch (NotFoundHttpException $e) {
            throw new UserNotFoundException(\sprintf('User with email %s not found', $username));
        }
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(\sprintf('Instances of %s are not supported', \get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        $user->setPassword($newEncodedPassword);

        $this->userRepository->save($user);
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }
}