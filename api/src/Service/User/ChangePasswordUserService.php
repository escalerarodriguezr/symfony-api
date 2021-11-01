<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Exception\User\UserInvalidPermissionException;
use App\Http\DTO\User\ChangePasswordUserRequest;
use App\Repository\User\Doctrine\DoctrineUserRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangePasswordUserService
{
    public function __construct(
        private DoctrineUserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function __invoke(string $id, ChangePasswordUserRequest $changePasswordUserRequest): void
    {
        $user = $this->userRepository->findOneByIdOrFail($id);

        if($user->getId() != $changePasswordUserRequest->getActionUserId()){
            throw UserInvalidPermissionException::fromChangePasswordUserService($changePasswordUserRequest->getActionUserId(), $user->getId());
        }

        if(!$this->passwordHasher->isPasswordValid($user, $changePasswordUserRequest->getOldPassword()))
        {
            throw new BadRequestHttpException('Invalid old password!');
        }

        $newHashedPassword = $this->passwordHasher->hashPassword($user, $changePasswordUserRequest->getNewPassword());

        $user->setPassword($newHashedPassword);
        $this->userRepository->save($user);

    }

}