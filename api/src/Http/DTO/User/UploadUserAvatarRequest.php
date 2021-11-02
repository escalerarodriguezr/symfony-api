<?php

declare(strict_types=1);

namespace App\Http\DTO\User;

use App\Http\DTO\RequestDTO;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UploadUserAvatarRequest implements RequestDTO
{

    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private ?string $actionUserId;

    /**
     * @Assert\NotBlank
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/*"},
     *     mimeTypesMessage = "Please upload a valid Image"
     * )
     */
    private ?UploadedFile $avatar;


    public function __construct(Request $request)
    {
        $this->actionUserId = $request->attributes->get('userId');
        $this->avatar = $request->files->get('avatar');
    }

    public function getActionUserId(): mixed
    {
        return $this->actionUserId;
    }

    public function getAvatar(): ?UploadedFile
    {
        return $this->avatar;
    }

}