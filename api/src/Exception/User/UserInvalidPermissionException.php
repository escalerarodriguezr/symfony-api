<?php
declare(strict_types=1);

namespace App\Exception\User;

class UserInvalidPermissionException extends \DomainException
{
    public static function fromChangePasswordUserService(string $actionUserId, string $userId): self
    {
        throw new self(\sprintf('User %s does not have permission to change password to user %s', $actionUserId, $userId));
    }
}