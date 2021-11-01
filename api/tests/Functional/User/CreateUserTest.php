<?php
declare(strict_types=1);

namespace App\Tests\Functional\User;

use App\Tests\Functional\FunctionalTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CreateUserTest extends FunctionalTestBase
{
    private const ENDPOINT = '/register/v1/user';

    public function testCreateUserAction(): void
    {
        $payload = [
            'name' => 'Brian',
            'email' => 'brian@api.com',
            'password' => 'password123'
        ];

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$baseClient->getResponse();

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $responseData = \json_decode($response->getContent(), true);

        self::assertArrayHasKey('user', $responseData);
    }

    public function testCreateUserActionWithInvalidEmail(): void
    {
        $payload = [
            'name' => 'Brian',
            'email' => 'brian',
            'password' => 'password123'
        ];

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$baseClient->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testCreateUserActionWithInvalidPassword(): void
    {
        $payload = [
            'name' => 'Brian',
            'email' => 'brian@api.com',
            'password' => 'pass'
        ];

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$baseClient->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

}