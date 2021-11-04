<?php

namespace App\Entity;

use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

class MovementCategory
{
    private string $id;
    private string $name;
    private User $owner;
    private \DateTime $createdOn;
    private \DateTime $updatedOn;

    public function __construct(string $name, User $owner)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->name = $name;
        $this->owner = $owner;
        $this->createdOn = new \DateTime();
        $this->markAsUpdated();
    }

    public static function createMovementCategory(string $name, User $owner): self
    {
        return new self($name, $owner);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCreatedOn(): \DateTime
    {
        return $this->createdOn;
    }

    public function getUpdatedOn(): \DateTime
    {
        return $this->updatedOn;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }


    public function markAsUpdated(): void
    {
        $this->updatedOn = new \DateTime();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'owner' => $this->owner->getId(),
            'createdOn' => $this->createdOn->format(DateTimeInterface::RFC3339),
            'updatedOn' => $this->updatedOn->format(DateTimeInterface::RFC3339),
        ];
    }

}