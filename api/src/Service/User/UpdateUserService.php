<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Http\DTO\User\UpdateUserRequest;
use App\Repository\User\Doctrine\DoctrineUserRepository;

class UpdateUserService
{
    public function __construct(private DoctrineUserRepository $userRepository)
    {
    }

    public function __invoke(string $id, UpdateUserRequest $updateUserRequest): User
    {
        $user = $this->userRepository->findOneByIdOrFail($id);

        if(!empty($updateUserRequest->getName())){
            $user->setName($updateUserRequest->getName());
        }

        if(!empty($updateUserRequest->getEmail())){
            $user->setEmail($updateUserRequest->getEmail());
        }

        $this->userRepository->save($user);

        return $user;
    }

}