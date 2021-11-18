<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Repository\User\Doctrine\DoctrineUserRepository;

class ActivateUserAccountByCodeService
{
    public function __construct(private DoctrineUserRepository $userRepository)
    {
    }

    public function __invoke(string $code): void
    {
        $user = $this->userRepository->findOneByActivationCodeOrFail($code);
        $user->setActive(true);
        $user->setConfirmedEmail(true);
        $user->setActivationCode(null);
        $this->userRepository->save($user);
    }

}