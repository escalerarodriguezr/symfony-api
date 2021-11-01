<?php
declare(strict_types=1);

namespace App\Tests\Functional\User;

use App\DataFixtures\UserFixtures;
use App\Exception\User\UserInvalidPermissionException;
use App\Tests\Functional\FunctionalTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ChangePasswordUserTest extends FunctionalTestBase
{
    private const ENDPOINT = '/api/v1/user';

    public function testChangePasswordUserAction(): void
    {
        $payload = [
            'oldPassword' => UserFixtures::PETER_PASSWORD,
            'newPassword' => 'password-new',
        ];

        $userId = $this->getPeterId();
        $endPoint = sprintf('%s/%s/change-password', self::ENDPOINT, $userId);

        self::$authenticatedClient->request(Request::METHOD_PUT, $endPoint, [], [], [], \json_encode($payload));

        $response = self::$authenticatedClient->getResponse();

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = \json_decode($response->getContent(), true);

        self::assertArrayHasKey('status', $responseData);
    }

    public function testChangePasswordWithInvalidOldPasswordUserAction(): void
    {
        $payload = [
            'oldPassword' => 'invalid-password',
            'newPassword' => 'password-new',
        ];

        $userId = $this->getPeterId();
        $endPoint = sprintf('%s/%s/change-password', self::ENDPOINT, $userId);

        self::$authenticatedClient->request(Request::METHOD_PUT, $endPoint, [], [], [], \json_encode($payload));

        $response = self::$authenticatedClient->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseData = \json_decode($response->getContent(), true);

        self::assertArrayHasKey('class', $responseData);
    }

    public function testChangePasswordUserWithInvalidUserAuthAction(): void
    {
        $payload = [
            'oldPassword' => UserFixtures::FRODO_PASSWORD,
            'newPassword' => 'password-new',
        ];

        $userId = $this->getFrodoId();
        $endPoint = sprintf('%s/%s/change-password', self::ENDPOINT, $userId);

        self::$authenticatedClient->request(Request::METHOD_PUT, $endPoint, [], [], [], \json_encode($payload));

        $response = self::$authenticatedClient->getResponse();

        self::assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        $responseData = \json_decode($response->getContent(), true);

        self::assertArrayHasKey('class', $responseData);
        self::assertEquals(UserInvalidPermissionException::class, $responseData['class']);
    }

}