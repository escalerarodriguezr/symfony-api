<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;


class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private string $id;
    private string $name;
    private string $email;
    private ?string $password;
    private ?string $activationCode;
    private ?bool $confirmedEmail;
    private ?string $avatarResource;
    private ?bool $active;
    private \DateTime $createdOn;
    private \DateTime $updatedOn;
    private Collection $movementCategories;

    public function __construct(string $name, string $email)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->name = $name;
        $this->email = $email;
        $this->password = null;
        $this->activationCode = \sha1(\uniqid());
        $this->confirmedEmail = false;
        $this->active = false;
        $this->avatarResource = null;
        $this->createdOn = new \DateTime();
        $this->markAsUpdated();
        $this->movementCategories = new ArrayCollection();

    }

    public static function createUser(string $name, string $email): self
    {
        return new self($name, $email);
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getCreatedOn(): \DateTime
    {
        return $this->createdOn;
    }

    /**
     * @return string|null
     */
    public function getActivationCode(): ?string
    {
        return $this->activationCode;
    }

    public function getConfirmedEmail(): ?bool
    {
        return $this->confirmedEmail;
    }

    public function setConfirmedEmail(?bool $confirmedEmail): void
    {
        $this->confirmedEmail = $confirmedEmail;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }


    public function getUpdatedOn(): \DateTime
    {
        return $this->updatedOn;
    }

    public function getAvatarResource(): ?string
    {
        return $this->avatarResource;
    }

    public function setAvatarResource(?string $avatarResource): void
    {
        $this->avatarResource = $avatarResource;
    }

    public function markAsUpdated(): void
    {
        $this->updatedOn = new \DateTime();
    }

    public function getMovementCategories(): ArrayCollection|Collection
    {
        return $this->movementCategories;
    }

    public function addMovementCategory(MovementCategory $entity): void
    {
        if ($this->movementCategories->contains($entity)) {
            return;
        }

        $this->movementCategories->add($entity);
    }

    public function removeMovementCategory(MovementCategory $entity): void
    {
        if ($this->movementCategories->contains($entity)) {
            $this->movementCategories->removeElement($entity);
        }
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatarResource' => $this->avatarResource,
            'movementCategories' => array_map(function (MovementCategory $entity): array {
                return $entity->toArray();
            }, $this->movementCategories->toArray()),
            'createdOn' => $this->createdOn->format(DateTimeInterface::RFC3339),
            'updatedOn' => $this->updatedOn->format(DateTimeInterface::RFC3339),
        ];
    }


    public function getRoles(): array
    {
        return [];
    }

    public function getSalt()
    {

    }

    public function eraseCredentials()
    {

    }

    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}