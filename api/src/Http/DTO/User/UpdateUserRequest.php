<?php

declare(strict_types=1);

namespace App\Http\DTO\User;

use App\Http\DTO\RequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserRequest implements RequestDTO
{
    /**
     * @Assert\Length(min=2)
     */
    private ?string $name;

    /**
     * @Assert\Email()
     */
    private ?string $email;


    public function __construct(Request $request)
    {
        $this->name = $request->request->get('name');
        $this->email = $request->request->get('email');
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

}