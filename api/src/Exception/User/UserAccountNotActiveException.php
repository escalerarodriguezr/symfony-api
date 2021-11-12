<?php
declare(strict_types=1);

namespace App\Exception\User;

class UserAccountNotActiveException extends \DomainException
{
    public static function fromLoginService(string $email): self
    {
        throw new self(\sprintf('User %s account is not active', $email));
    }

}