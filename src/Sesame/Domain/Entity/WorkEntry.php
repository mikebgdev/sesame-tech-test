<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

namespace App\Sesame\Domain\Entity;

use App\Sesame\Infrastructure\Repository\WorkEntryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[OA\Schema(
    schema: 'WorkEntry',
    title: 'WorkEntry',
    description: 'WorkEntry entity',
    required: ['id', 'userId', 'startDate', 'endDate'],
    type: 'object'
)]
#[ORM\Entity(repositoryClass: WorkEntryRepository::class)]
class WorkEntry
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[OA\Property(property: 'id', description: 'Unique identifier', type: 'string')]
    private UuidInterface $id;

    #[ORM\Column(type: 'uuid')]
    #[OA\Property(property: 'userId', description: 'User identifier', type: 'string')]
    private UuidInterface $userId;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[OA\Property(property: 'startDate', description: 'Start entry timestamp', type: 'string', format: 'date-time')]
    private \DateTime $startDate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[OA\Property(property: 'endDate', description: 'End entry timestamp', type: 'string', format: 'date-time')]
    private ?\DateTime $endDate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[OA\Property(property: 'createdAt', description: 'Creation timestamp', type: 'string', format: 'date-time')]
    private \DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[OA\Property(property: 'updatedAt', description: 'Last update timestamp', type: 'string', format: 'date-time')]
    private \DateTime $updatedAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[OA\Property(property: 'deletedAt', description: 'Deletion timestamp', type: 'string', format: 'date-time', nullable: true)]
    private ?\DateTime $deletedAt;

    public function __construct(UuidInterface $userId)
    {
        $this->userId = $userId;
        $this->startDate = new \DateTime();
        $this->endDate = null;
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

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function setUserId(UuidInterface $userId): void
    {
        $this->userId = $userId;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
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
}
