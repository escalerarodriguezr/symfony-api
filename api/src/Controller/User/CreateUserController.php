<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Http\DTO\User\CreateUserRequest;
use App\Service\User\CreateUserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateUserController
{
    public function __construct(private CreateUserService $createUserService)
    {

    }

    public function __invoke(CreateUserRequest $request): JsonResponse
    {
        $user = $this->createUserService->__invoke($request);
        return new JsonResponse(
            [
                'user' => $user->toArray()
            ],
            Response::HTTP_CREATED
        );
    }

}