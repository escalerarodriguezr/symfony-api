<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\MovementCategory;
use App\Exception\User\UserInvalidPermissionException;
use App\Http\DTO\User\CreateUserCategoryMovementRequest;
use App\Repository\MovementCategory\Doctrine\DoctrineMovementCategoryRepository;
use App\Repository\User\Doctrine\DoctrineUserRepository;

class CreateUserCategoryMovementService
{
    public function __construct(
        private DoctrineUserRepository $userRepository,
        private DoctrineMovementCategoryRepository $movementCategoryRepository
    )
    {
    }

    public function __invoke(string $id, CreateUserCategoryMovementRequest $input): MovementCategory
    {
        $user = $this->userRepository->findOneByIdOrFail($id);

        if($user->getId() != $input->getActionUserId()){
            throw UserInvalidPermissionException::fromCreateUserCategoryMovementService($input->getActionUserId(), $user->getId());
        }

        $movementCategory = MovementCategory::createMovementCategory($input->getName(), $user);
        $this->movementCategoryRepository->save($movementCategory);
        return $movementCategory;

    }

}