<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Service;



use App\Entity\User;
use App\Http\DTO\User\CreateUserRequest;
use App\Repository\User\Doctrine\DoctrineUserRepository;
use App\Service\User\CreateUserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserServiceUnitTest extends TestCase
{
    private MockObject|DoctrineUserRepository $userRepository;
    private MockObject|UserPasswordHasherInterface $userPasswordHasher;
    private MockObject|CreateUserRequest $createUserRequest;
    private CreateUserService $createUserService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->getMockBuilder(DoctrineUserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userPasswordHasher = $this->getMockBuilder(UserPasswordHasherInterface::class)
            ->disableOriginalConstructor()
            ->addMethods(['hashPassword'])
            ->getMock();
        $this->createUserRequest = $this->getMockBuilder(CreateUserRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->createUserService = new CreateUserService($this->userRepository, $this->userPasswordHasher);

    }

    public function testCreateUserService(): void
    {
        $this->createUserRequest->method('getName')->willReturn('Cesar');
        $this->createUserRequest->method('getEmail')->willReturn('cesar@api.com');
        $this->createUserRequest->method('getPassword')->willReturn('password123');

        $this->userPasswordHasher
            ->expects($this->exactly(1))
            ->method('hashPassword')
            ->with($this->isInstanceOf(User::class), $this->createUserRequest->getPassword())
            ->willReturn('encoded-password');

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('save')
            ->with($this->isInstanceOf(User::class));

        $response = $this->createUserService->__invoke($this->createUserRequest);

        self::assertInstanceOf(User::class, $response);

    }

}