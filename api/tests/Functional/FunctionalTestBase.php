<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\UserFixtures;
use App\Repository\User\Doctrine\DoctrineUserRepository;
use Doctrine\DBAL\Connection;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FunctionalTestBase extends WebTestCase
{

    private static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $baseClient = null;
    protected static ?KernelBrowser $authenticatedClient = null;

    public function setUp(): void
    {
        parent::setUp();

        if (null === self::$client) {
            self::$client = static::createClient();
        }

        if (null === self::$baseClient) {
            self::$baseClient = clone self::$client;
            self::$baseClient->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]);
        }

        if (null === self::$authenticatedClient) {
            self::$authenticatedClient = clone self::$client;

            $user = static::getContainer()->get(DoctrineUserRepository::class)->findOneByEmailOrFail('peter@api.com');
            $token = static::getContainer()->get(JWTTokenManagerInterface::class)->create($user);

            self::$authenticatedClient->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_Authorization' => \sprintf('Bearer %s', $token),
            ]);
        }
    }

    protected static function initDBConnection(): Connection
    {
        if (null === static::$kernel) {
            static ::bootKernel();
        }

        return static::$kernel->getContainer()->get('doctrine')->getConnection();
    }

    protected function getPeterId()
    {
        $query = sprintf('SELECT id FROM user WHERE email = "%s"', UserFixtures::PETER_EMAIL);
        return self::initDBConnection()->executeQuery($query)->fetchOne();
    }

    protected function getFrodoId()
    {
        $query = sprintf('SELECT id FROM user WHERE email = "%s"', UserFixtures::FRODO_EMAIL);
        return self::initDBConnection()->executeQuery($query)->fetchOne();
    }
}