<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Service;

use App\Entity\User;
use App\Repository\User\Doctrine\DoctrineUserRepository;
use App\Service\User\GetUserByIdService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetUserByIdServiceUnitTest extends TestCase
{
    private MockObject|DoctrineUserRepository $userRepository;
    private GetUserByIdService $getUserByIdService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->getMockBuilder(DoctrineUserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->getUserByIdService = new GetUserByIdService($this->userRepository);

    }

    public function testGetUserByIdService(): void
    {
        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdOrFail')
            ->with($this->isType('string'))
            ->willReturn($this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock())
        ;

        $response = $this->getUserByIdService->__invoke('fake-user-id');

        self::assertInstanceOf(User::class, $response);
    }

}