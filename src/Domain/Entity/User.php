<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

namespace App\Domain\Entity;

use App\Infrastructure\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[OA\Schema(
    schema: 'User',
    title: 'User',
    description: 'User entity',
    required: ['id', 'name', 'email', 'password'],
    type: 'object'
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[OA\Property(property: 'id', description: 'Unique identifier', type: 'string')]
    private UuidInterface $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[OA\Property(property: 'name', description: 'Name of the user', type: 'string')]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[OA\Property(property: 'email', description: 'Email of the user', type: 'string')]
    private string $email;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[OA\Property(property: 'password', description: 'Hashed password', type: 'string')]
    private string $password;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[OA\Property(property: 'createdAt', description: 'Creation timestamp', type: 'string', format: 'date-time')]
    private \DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[OA\Property(property: 'updatedAt', description: 'Last update timestamp', type: 'string', format: 'date-time')]
    private \DateTime $updatedAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[OA\Property(property: 'deletedAt', description: 'Deletion timestamp', type: 'string', format: 'date-time', nullable: true)]
    private ?\DateTime $deletedAt;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = '';
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->deletedAt = null;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): void
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

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTime $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
