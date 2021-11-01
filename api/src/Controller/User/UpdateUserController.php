<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Http\DTO\User\UpdateUserRequest;
use App\Service\User\UpdateUserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserController
{
    public function __construct(private UpdateUserService $updateUserService)
    {

    }

    public function __invoke(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = $this->updateUserService->__invoke($id,$request);
        return new JsonResponse(
            [
                'user' => $user->toArray()
            ],
            Response::HTTP_CREATED
        );
    }

}