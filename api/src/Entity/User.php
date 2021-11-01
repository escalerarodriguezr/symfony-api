<?php

namespace App\Entity;

use DateTimeInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;



class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private string $id;
    private string $name;
    private string $email;
    private ?string $password;
    private \DateTime $createdOn;
    private \DateTime $updatedOn;

    public function __construct(string $name, string $email)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->name = $name;
        $this->email = $email;
        $this->password = null;
        $this->createdOn = new \DateTime();
        $this->markAsUpdated();
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

    public function getUpdatedOn(): \DateTime
    {
        return $this->updatedOn;
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
            'email' => $this->email,
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