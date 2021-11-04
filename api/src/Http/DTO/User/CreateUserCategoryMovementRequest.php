<?php

declare(strict_types=1);

namespace App\Http\DTO\User;

use App\Http\DTO\RequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserCategoryMovementRequest implements RequestDTO
{

    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private ?string $actionUserId;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=4)
     */
    private ?string $name;


    public function __construct(Request $request)
    {
        $this->actionUserId = $request->attributes->get('userId');
        $this->name = $request->request->get('name');
    }

    public function getActionUserId(): string
    {
        return $this->actionUserId;
    }


    public function getName(): string
    {
        return $this->name;
    }

}