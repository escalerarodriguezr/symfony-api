<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Service\User\ActivateUserAccountByCodeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PutUserActivateAccountByCodeController
{
    public function __construct( private ActivateUserAccountByCodeService $service)
    {

    }

    public function __invoke(string $code): JsonResponse
    {
        $this->service->__invoke($code);
        return new JsonResponse(
            null,
            Response::HTTP_OK
        );
    }

}