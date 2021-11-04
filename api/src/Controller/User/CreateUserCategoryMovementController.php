<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Http\DTO\User\CreateUserCategoryMovementRequest;
use App\Service\User\CreateUserCategoryMovementService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateUserCategoryMovementController
{
    public function __construct(private CreateUserCategoryMovementService $createUserCategoryMovementService)
    {

    }

    public function __invoke(string $id, CreateUserCategoryMovementRequest $input): JsonResponse
    {

        $entity = $this->createUserCategoryMovementService->__invoke($id,$input);
        return new JsonResponse(
            [
                'movementCategory' => $entity->toArray()
            ],
            Response::HTTP_CREATED
        );
    }

}