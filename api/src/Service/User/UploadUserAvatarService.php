<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Http\DTO\User\UploadUserAvatarRequest;
use App\Process\Shared\File\FileProcess;
use App\Repository\User\Doctrine\DoctrineUserRepository;

class UploadUserAvatarService
{
    public function __construct(private DoctrineUserRepository $userRepository, private FileProcess $fileProcess)
    {
    }


    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function __invoke(string $userId, UploadUserAvatarRequest $uploadUserAvatarRequest): string
    {
        $user = $this->userRepository->findOneByIdOrFail($userId);
        $this->fileProcess->deleteFile($user->getAvatarResource());
        $fileName= $this->fileProcess->uploadFile($uploadUserAvatarRequest->getAvatar(), 'avatar', FileProcess::VISIBILITY_PUBLIC);
        $user->setAvatarResource($fileName);
        $this->userRepository->save($user);
        return $fileName;
    }

}