<?php

namespace App\Domain\Entity;

use App\Infrastructure\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;

#[OA\Schema(
    schema: "User",
    title: "User",
    description: "User entity",
    required: ["id", "name", "email", "password"],
    type: "object"
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[OA\Property(property: "id", description: "Unique identifier", type: "string")]
    private Uuid $id;

    #[ORM\Column(type: "string", length: 255)]
    #[OA\Property(property: "name", description: "Name of the user", type: "string")]
    private string $name;

    #[ORM\Column(type: "string", length: 255)]
    #[OA\Property(property: "email", description: "Email of the user", type: "string")]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    #[OA\Property(property: "password", description: "Hashed password", type: "string")]
    private string $password;

    #[ORM\Column(type: "datetime")]
    #[OA\Property(property: "createdAt", description: "Creation timestamp", type: "string", format: "date-time")]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: "datetime")]
    #[OA\Property(property: "updatedAt", description: "Last update timestamp", type: "string", format: "date-time")]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: "datetime", nullable: true)]
    #[OA\Property(property: "deletedAt", description: "Deletion timestamp", type: "string", format: "date-time", nullable: true)]
    private ?\DateTimeImmutable $deletedAt;

    public function __construct(string $name, string $email, string $password)
    {
        $this->id = Uuid::v4();
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->deletedAt = null;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }


}
