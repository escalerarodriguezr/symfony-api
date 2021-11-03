<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Http\DTO\User\UploadUserAvatarRequest;
use App\Service\User\UploadUserAvatarService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UploadUserAvatarController
{
    public function __construct(private UploadUserAvatarService $uploadUserAvatarService)
    {

    }
    
    public function __invoke(string $userId, UploadUserAvatarRequest $uploadUserAvatarRequest): JsonResponse
    {

        $avatarResource = $this->uploadUserAvatarService->__invoke($userId, $uploadUserAvatarRequest);
        return new JsonResponse(
            [
                'avatarResource' => $avatarResource
            ],
            Response::HTTP_CREATED
        );
    }

}