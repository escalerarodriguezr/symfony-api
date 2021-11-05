<?php
declare(strict_types=1);

namespace App\Tests\Functional\User;

use App\DataFixtures\UserFixtures;
use App\Tests\Functional\FunctionalTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LoginUserActionTest extends FunctionalTestBase
{
    private const ENDPOINT = '/api/login_check';

    public function testLoginAccountInvalidAction(): void
    {
        $payload = [
            'username' => 'brian@api.com',
            'password' => 'password123'
        ];

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$baseClient->getResponse();

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

    }

    public function testLoginSuccessAction(): void
    {
        $payload = [

            'username' => UserFixtures::PETER_EMAIL,
            'password' => UserFixtures::PETER_PASSWORD
        ];

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$baseClient->getResponse();
        $responseData = \json_decode($response->getContent(), true);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertArrayHasKey('token', $responseData);
    }

    public function testLoginNotActiveAccountAction(): void
    {
        $payload = [

            'username' => UserFixtures::NOT_EMAIL_CONFIRMED_EMAIL,
            'password' => UserFixtures::NOT_EMAIL_CONFIRMED_PASSWORD
        ];

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$baseClient->getResponse();
        $responseData = \json_decode($response->getContent(), true);

        self::assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        self::assertArrayHasKey('class', $responseData);
    }

}