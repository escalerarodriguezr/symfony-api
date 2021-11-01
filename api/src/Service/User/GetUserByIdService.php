<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Http\DTO\User\UpdateUserRequest;
use App\Repository\User\Doctrine\DoctrineUserRepository;

class GetUserByIdService
{
    public function __construct(private DoctrineUserRepository $userRepository)
    {
    }

    public function __invoke(string $id): User
    {
        return $this->userRepository->findOneByIdOrFail($id);

    }

}