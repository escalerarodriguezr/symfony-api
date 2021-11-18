<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Service;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Repository\User\Doctrine\DoctrineUserRepository;
use App\Service\User\ActivateUserAccountByCodeService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ActivateUserAccountByCodeServiceUnitTest extends TestCase
{
    private MockObject|DoctrineUserRepository $userRepository;
    private ActivateUserAccountByCodeService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->getMockBuilder(DoctrineUserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->service = new ActivateUserAccountByCodeService($this->userRepository);

    }

    public function testActivateAccountSuccessService(): void
    {
        $user = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByActivationCodeOrFail')
            ->with($this->isType('string'))
            ->willReturn($user)
        ;

        $user->expects($this->exactly(1))
            ->method('setActive')
            ->with(true);
        $user->expects($this->exactly(1))
             ->method('setConfirmedEmail')
             ->with(true);
        $user->expects($this->exactly(1))
             ->method('setActivationCode')
             ->with(null);

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('save')
            ->with($this->isInstanceOf(User::class));

        $this->service->__invoke('fake-code');

    }

    public function testActivateAccountExceptionUserNotFoundService(): void
    {
        $user = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByActivationCodeOrFail')
            ->with($this->isType('string'))
            ->willThrowException(new UserNotFoundException('fake-message'))
        ;

        $user->expects($this->exactly(0))
            ->method('setActive')
            ->with(true);
        $user->expects($this->exactly(0))
            ->method('setConfirmedEmail')
            ->with(true);
        $user->expects($this->exactly(0))
            ->method('setActivationCode')
            ->with(null);

        $this->userRepository
            ->expects($this->exactly(0))
            ->method('save')
            ->with($this->isInstanceOf(User::class));

        self::expectException(UserNotFoundException::class);

        $this->service->__invoke('fake-code');

    }

}