<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use App\Tests\Functional\FunctionalTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PutUserActivateAccountByCodeActionTest extends FunctionalTestBase
{

    private const ENDPOINT = '/activate-account';

    public function testPutActivateAccountSuccessAction(): void
    {
        $code = $this->getMelkorActivationCode();
        self::$authenticatedClient->request(Request::METHOD_PUT, \sprintf('%s/%s', self::ENDPOINT, $code));

        $response = self::$authenticatedClient->getResponse();

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testPutActivateAccountUserNotFoundException(): void
    {
        self::$authenticatedClient->request(Request::METHOD_PUT, \sprintf('%s/8b1f842b-d19', self::ENDPOINT));
        $response = self::$authenticatedClient->getResponse();
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}