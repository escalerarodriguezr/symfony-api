<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Http\DTO\User\ChangePasswordUserRequest;
use App\Service\User\ChangePasswordUserService;
use Symfony\Component\HttpFoundation\JsonResponse;


class ChangePasswordUserController
{
    public function __construct(private ChangePasswordUserService $changePasswordUserService)
    {

    }

    public function __invoke(ChangePasswordUserRequest $changePasswordUserRequest, string $id): JsonResponse
    {
        $this->changePasswordUserService->__invoke($id,$changePasswordUserRequest);
        return new JsonResponse(['status' => 'Password has been changed!']);
    }

}