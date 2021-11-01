<?php

declare(strict_types=1);

namespace App\Http\DTO\User;

use App\Http\DTO\RequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordUserRequest implements RequestDTO
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private ?string $actionUserId;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=6)
     */
    private ?string $oldPassword;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=6)
     */
    private ?string $newPassword;


    public function __construct(Request $request)
    {
        $this->actionUserId = $request->attributes->get('userId');
        $this->oldPassword = $request->request->get('oldPassword');
        $this->newPassword = $request->request->get('newPassword');
    }


    public function getActionUserId(): ?string
    {
        return $this->actionUserId;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }


}