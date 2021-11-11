<?php
declare(strict_types=1);

namespace App\Messenger\Message\User;

class RegisterUserMessage
{
    public function __construct(
        private string $fullname,
        private string $email,
        private string $activationCode
    )
    {
    }


    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getActivationCode(): string
    {
        return $this->activationCode;
    }



}