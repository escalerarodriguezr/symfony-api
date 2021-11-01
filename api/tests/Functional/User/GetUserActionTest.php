<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use App\DataFixtures\UserFixtures;
use App\Exception\User\UserNotFoundException;
use App\Tests\Functional\FunctionalTestBase;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetUserActionTest extends FunctionalTestBase
{

    private const ENDPOINT = '/api/v1/user';

    public function testGetUserByIdAction(): void
    {
        $userId = $this->getPeterId();
        self::$authenticatedClient->request(Request::METHOD_GET, \sprintf('%s/%s', self::ENDPOINT, $userId));

        $response = self::$authenticatedClient->getResponse();

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = \json_decode($response->getContent(), true);

        self::assertEquals(UserFixtures::PETER_NAME, $responseData['user']['name']);
    }

    public function testGetUserActionForNonExistingUserId(): void
    {
        self::$authenticatedClient->request(Request::METHOD_GET, \sprintf('%s/8b1f842b-d195-4fdf-967a-f51dc65ae532', self::ENDPOINT));

        $response = self::$authenticatedClient->getResponse();
        $responseData = \json_decode($response->getContent(), true);

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        self::assertEquals(UserNotFoundException::class, $responseData['class']);
    }
}