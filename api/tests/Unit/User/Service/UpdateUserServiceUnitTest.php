<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Service;

use App\Entity\User;
use App\Http\DTO\User\UpdateUserRequest;
use App\Repository\User\Doctrine\DoctrineUserRepository;
use App\Service\User\UpdateUserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;


class UpdateUserServiceUnitTest extends TestCase
{
    private MockObject|DoctrineUserRepository $userRepository;
    private MockObject|UpdateUserRequest $updateUserRequest;
    private UpdateUserService $updateUserService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->getMockBuilder(DoctrineUserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->updateUserRequest = $this->getMockBuilder(UpdateUserRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->updateUserService = new UpdateUserService($this->userRepository);

    }

    public function testUpdateUserService(): void
    {
        $this->updateUserRequest->method('getName')->willReturn('new-name');
        $this->updateUserRequest->method('getEmail')->willReturn('new-email@api.com');

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdOrFail')
            ->with($this->isType('string'))
            ->willReturn($user)
        ;

        $this->updateUserRequest
            ->expects($this->exactly(2))
            ->method('getName')
            ->willReturn('new-name');

        $user->expects($this->exactly(1))
            ->method('setName')
            ->with($this->isType('string'));

        $this->updateUserRequest
            ->expects($this->exactly(2))
            ->method('getEmail')
            ->willReturn('new-email@api.com');

        $user->expects($this->exactly(1))
            ->method('setEmail')
            ->with($this->isType('string'));

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('save')
            ->with($user);

        $response = $this->updateUserService->__invoke('fake-user-id',$this->updateUserRequest);

        self::assertInstanceOf(User::class, $response);

    }

    public function testUpdateUserOnlyEmailService(): void
    {
        $this->updateUserRequest->method('getName')->willReturn(null);
        $this->updateUserRequest->method('getEmail')->willReturn('new-email@api.com');

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdOrFail')
            ->with($this->isType('string'))
            ->willReturn($user)
        ;

        $this->updateUserRequest
            ->expects($this->exactly(1))
            ->method('getName')
            ->willReturn(null);
        
        $this->updateUserRequest
            ->expects($this->exactly(2))
            ->method('getEmail')
            ->willReturn('new-email@api.com');

        $user->expects($this->exactly(1))
            ->method('setEmail')
            ->with($this->isType('string'));

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('save')
            ->with($user);

        $response = $this->updateUserService->__invoke('fake-user-id',$this->updateUserRequest);

        self::assertInstanceOf(User::class, $response);

    }

}