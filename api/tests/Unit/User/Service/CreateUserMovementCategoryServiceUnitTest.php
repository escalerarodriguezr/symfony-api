<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Service;

use App\Entity\MovementCategory;
use App\Entity\User;
use App\Exception\User\UserInvalidPermissionException;
use App\Http\DTO\User\ChangePasswordUserRequest;
use App\Http\DTO\User\CreateUserCategoryMovementRequest;
use App\Repository\MovementCategory\Doctrine\DoctrineMovementCategoryRepository;
use App\Repository\User\Doctrine\DoctrineUserRepository;
use App\Service\User\ChangePasswordUserService;
use App\Service\User\CreateUserCategoryMovementService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserMovementCategoryServiceUnitTest extends TestCase
{
    private MockObject|DoctrineUserRepository $userRepository;
    private MockObject|DoctrineMovementCategoryRepository $movementCategoryRepository;
    private CreateUserCategoryMovementService $service;


    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->getMockBuilder(DoctrineUserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->movementCategoryRepository = $this->getMockBuilder(DoctrineMovementCategoryRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->service = new CreateUserCategoryMovementService($this->userRepository, $this->movementCategoryRepository);

    }

    public function testCreateMovementCategoryService(): void
    {
        $userId = 'user-id';
        $input = $this->getMockBuilder(CreateUserCategoryMovementRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
        $input->method('getName')->willReturn('category-name');
        $input->method('getActionUserId')->willReturn($userId);

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user->method('getId')->willReturn($userId);

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdOrFail')
            ->with($userId)
            ->willReturn($user);

        $user->expects($this->exactly(1))
            ->method('getId');
        $input->expects($this->exactly(1))
            ->method('getActionUserId');

        $this->movementCategoryRepository
            ->expects($this->exactly(1))
            ->method('save')
            ->with($this->isInstanceOf(MovementCategory::class));

        $response = $this->service->__invoke($userId,$input);

        self::assertInstanceOf(MovementCategory::class, $response);
    }

    public function testCreateMovementCategoryUserInvalidPermissionService(): void
    {
        $userId = 'user-id';
        $input = $this->getMockBuilder(CreateUserCategoryMovementRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
        $input->method('getName')->willReturn('category-name');
        $input->method('getActionUserId')->willReturn('ivalid-user-id');

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user->method('getId')->willReturn($userId);

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdOrFail')
            ->with($userId)
            ->willReturn($user);

        $user->expects($this->exactly(2))
            ->method('getId');
        $input->expects($this->exactly(2))
            ->method('getActionUserId');

        try{
            $response = $this->service->__invoke($userId,$input);
        }catch (UserInvalidPermissionException $e){
            self::assertInstanceOf(UserInvalidPermissionException::class, $e);
        }
    }

}