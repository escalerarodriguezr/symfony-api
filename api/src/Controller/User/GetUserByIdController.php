<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Service\User\GetUserByIdService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetUserByIdController
{
    public function __construct(private GetUserByIdService $getUserByIdService)
    {

    }

    public function __invoke(string $id): JsonResponse
    {

        $user = $this->getUserByIdService->__invoke($id);
        return new JsonResponse(
            [
                'user' => $user->toArray()
            ],
            Response::HTTP_OK
        );
    }

}