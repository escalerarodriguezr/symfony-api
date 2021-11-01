<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Service;

use App\Entity\User;
use App\Exception\User\UserInvalidPermissionException;
use App\Http\DTO\User\ChangePasswordUserRequest;
use App\Repository\User\Doctrine\DoctrineUserRepository;
use App\Service\User\ChangePasswordUserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangeUserPasswordServiceUnitTest extends TestCase
{
    private MockObject|DoctrineUserRepository $userRepository;
    private MockObject|UserPasswordHasherInterface $userPasswordHasher;
    private MockObject|ChangePasswordUserRequest $changePasswordUserRequest;
    private ChangePasswordUserService $changePasswordUserService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->getMockBuilder(DoctrineUserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userPasswordHasher = $this->getMockBuilder(UserPasswordHasherInterface::class)
            ->disableOriginalConstructor()
            ->addMethods(['hashPassword', 'isPasswordValid'])
            ->getMock();
        $this->changePasswordUserRequest = $this->getMockBuilder(ChangePasswordUserRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->changePasswordUserService = new ChangePasswordUserService($this->userRepository, $this->userPasswordHasher);

    }

    public function testChangePasswordUserService(): void
    {
        $this->changePasswordUserRequest->method('getOldPassword')->willReturn('old-password');
        $this->changePasswordUserRequest->method('getNewPassword')->willReturn('new-password');
        $this->changePasswordUserRequest->method('getActionUserId')->willReturn('action-user-id');

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user->method('getId')->willReturn('action-user-id');
        $user->method('getPassword')->willReturn('old-password');

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdOrFail')
            ->with($this->changePasswordUserRequest->getActionUserId())
            ->willReturn($user);

        $this->userPasswordHasher
            ->expects($this->exactly(1))
            ->method('isPasswordValid')
            ->with($user, $this->changePasswordUserRequest->getOldPassword())
            ->willReturn(true);

        $this->userPasswordHasher
            ->expects($this->exactly(1))
            ->method('hashPassword')
            ->with($user, $this->changePasswordUserRequest->getNewPassword())
            ->willReturn('encoded-password');

        $user->expects($this->exactly(1))
            ->method('setPassword')
            ->with('encoded-password');

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('save')
            ->with($user);

        $this->changePasswordUserService->__invoke('action-user-id',$this->changePasswordUserRequest);
    }

    public function testChangePasswordUserServiceWithIsOldPasswordInvalid(): void
    {
        $this->changePasswordUserRequest->method('getOldPassword')->willReturn('old-password');
        $this->changePasswordUserRequest->method('getNewPassword')->willReturn('new-password');
        $this->changePasswordUserRequest->method('getActionUserId')->willReturn('action-user-id');

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user->method('getId')->willReturn('action-user-id');
        $user->method('getPassword')->willReturn('old-password-invalid');

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdOrFail')
            ->with($this->changePasswordUserRequest->getActionUserId())
            ->willReturn($user);

        $this->userPasswordHasher
            ->expects($this->exactly(1))
            ->method('isPasswordValid')
            ->with($user, $this->changePasswordUserRequest->getOldPassword())
            ->willReturn(false);
        try{
            $this->changePasswordUserService->__invoke('action-user-id',$this->changePasswordUserRequest);
        }catch (BadRequestHttpException $e){
           self::assertInstanceOf(BadRequestHttpException::class, $e);
        }
    }

    public function testChangePasswordUserServiceWithInvalidActionUserId(): void
    {
        $this->changePasswordUserRequest->method('getOldPassword')->willReturn('old-password');
        $this->changePasswordUserRequest->method('getNewPassword')->willReturn('new-password');
        $this->changePasswordUserRequest->method('getActionUserId')->willReturn('action-user-id');

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user->method('getId')->willReturn('action-user-id-invalid');
        $user->method('getPassword')->willReturn('old-password-invalid');

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdOrFail')
            ->with($this->changePasswordUserRequest->getActionUserId())
            ->willReturn($user);

        try{
            $this->changePasswordUserService->__invoke('action-user-id',$this->changePasswordUserRequest);
        }catch (UserInvalidPermissionException $e){
            self::assertInstanceOf(UserInvalidPermissionException::class, $e);
        }
    }
}