<?php
declare(strict_types=1);

namespace App\Tests\Functional\User;

use App\Exception\User\UserInvalidPermissionException;
use App\Tests\Functional\FunctionalTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CreateUserCategoryMovementActionTest extends FunctionalTestBase
{
    private const ENDPOINT = '/api/v1/user';

    public function testCreateUserCategoryMovementAction(): void
    {
        $payload = [
            'name' => 'Home',
        ];

        $userId = $this->getPeterId();
        $endPoint = sprintf('%s/%s/category-movement', self::ENDPOINT, $userId);

        self::$authenticatedClient->request(Request::METHOD_POST, $endPoint, [], [], [], \json_encode($payload));

        $response = self::$authenticatedClient->getResponse();

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $responseData = \json_decode($response->getContent(), true);

        self::assertArrayHasKey('movementCategory', $responseData);
        $entity = $responseData['movementCategory'];
        self::assertArrayHasKey('owner', $entity);
        self::assertEquals($userId,$entity['owner']);

    }

    public function testCreateUserCategoryMovementWithInvalidUserPermissionAction(): void
    {
        $payload = [
            'name' => 'Home',
        ];

        $userId = $this->getFrodoId();
        $endPoint = sprintf('%s/%s/category-movement', self::ENDPOINT, $userId);

        self::$authenticatedClient->request(Request::METHOD_POST, $endPoint, [], [], [], \json_encode($payload));

        $response = self::$authenticatedClient->getResponse();

        self::assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        $responseData = \json_decode($response->getContent(), true);

        self::assertArrayHasKey('class', $responseData);
        self::assertEquals(UserInvalidPermissionException::class, $responseData['class']);
        self::assertArrayHasKey('class', $responseData);

    }

}