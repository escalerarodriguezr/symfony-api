<?php
declare(strict_types=1);

namespace App\Tests\Functional\User;

use App\Tests\Functional\FunctionalTestBase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadUserAvatarActionTest extends FunctionalTestBase
{

    private const ENDPOINT = '/api/v1/user';

    public function testUploadUserAvatarAction(): void
    {
        $avatar = new UploadedFile(
            __DIR__.'/../../../src/DataFixtures/avatar.png',
            'avatar.png'
        );

        $userId = $this->getPeterId();
        $endPoint = sprintf('%s/%s/avatar', self::ENDPOINT, $userId);

        self::$authenticatedClient->request(Request::METHOD_POST, $endPoint, [],  ['avatar' => $avatar]);

        $response = self::$authenticatedClient->getResponse();

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $responseData = \json_decode($response->getContent(), true);

        self::assertArrayHasKey('avatarResource', $responseData);
    }

    public function testUploadUserAvatarBadMimeAction(): void
    {
        $avatar = new UploadedFile(
            __DIR__.'/../../../src/DataFixtures/fake.txt',
            'fake.txt'
        );

        $userId = $this->getPeterId();
        $endPoint = sprintf('%s/%s/avatar', self::ENDPOINT, $userId);

        self::$authenticatedClient->request(Request::METHOD_POST, $endPoint, [],  ['avatar' => $avatar]);

        $response = self::$authenticatedClient->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseData = \json_decode($response->getContent(), true);

    }

}