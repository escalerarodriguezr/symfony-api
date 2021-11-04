<?php

declare(strict_types=1);

namespace App\Exception\MovementCategory;

class MovementCategoryNotFoundException extends \DomainException
{

    public static function fromId(string $id): self
    {
        throw new self(\sprintf('MovementCategory with ID %s not found', $id));
    }
}