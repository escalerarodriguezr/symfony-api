<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Service;

use App\Entity\User;
use App\Http\DTO\User\UploadUserAvatarRequest;
use App\Process\Shared\File\FileProcess;
use App\Repository\User\Doctrine\DoctrineUserRepository;
use App\Service\User\UploadUserAvatarService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadUserAvatarServiceUnitTest extends TestCase
{
    private MockObject|DoctrineUserRepository $doctrineUserRepository;
    private MockObject|FileProcess $fileProcess;
    private UploadUserAvatarService $uploadUserAvatarService;

    public function setUp(): void
    {
        parent::setUp();

        $this->doctrineUserRepository = $this->getMockBuilder(DoctrineUserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fileProcess = $this->getMockBuilder(FileProcess::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->uploadUserAvatarService = new UploadUserAvatarService($this->doctrineUserRepository, $this->fileProcess);
    }

    public function testUploadUserAvatarService(): void
    {
        $userId = 'user-id';

        $uploadAvatarRequest = $this->getMockBuilder(UploadUserAvatarRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
        $file= $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $uploadAvatarRequest->method('getAvatar')->willReturn($file);
        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->doctrineUserRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdOrFail')
            ->with($userId)
            ->willReturn($user);

        $this->fileProcess
            ->expects($this->exactly(1))
            ->method('deleteFile')
            ->with($user->getAvatarResource());

        $this->fileProcess
            ->expects($this->exactly(1))
            ->method('uploadFile')
            ->with($uploadAvatarRequest->getAvatar(), 'avatar', FileProcess::VISIBILITY_PUBLIC)
            ->willReturn('aaa.png');

        $user->expects($this->exactly(1))
            ->method('setAvatarResource')
            ->with('aaa.png');

        $this->doctrineUserRepository
            ->expects($this->exactly(1))
            ->method('save')
            ->with($user);

        $response = $this->uploadUserAvatarService->__invoke($userId, $uploadAvatarRequest);

        self::assertIsString($response);
        
    }


}