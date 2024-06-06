<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Domain\Entity;

use App\Sesame\Domain\Entity\WorkEntry;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class WorkEntryTest extends TestCase
{
    public function testWorkEntryConstructor(): void
    {
        $userId = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');

        $workEntry = new WorkEntry($userId);

        self::assertSame($userId->toString(), $workEntry->getUserId()->toString());
        self::assertNotEmpty($workEntry->getStartDate());
        self::assertNull($workEntry->getEndDate());
        self::assertNotEmpty($workEntry->getCreatedAt());
        self::assertNotEmpty($workEntry->getUpdatedAt());
        self::assertNull($workEntry->getDeletedAt());
    }

    public function testGetAndSetId(): void
    {
        $uuid = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');
        $workEntry = new WorkEntry($uuid);

        $workEntry->setId($uuid);

        self::assertInstanceOf(UuidInterface::class, $workEntry->getId());
        self::assertSame($uuid->toString(), $workEntry->getId()->toString());
    }

    public function testGetAndSetUserId(): void
    {
        $uuid = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');
        $workEntry = new WorkEntry($uuid);
        $newUserId = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');

        $workEntry->setUserId($newUserId);

        self::assertSame($newUserId->toString(), $workEntry->getUserId()->toString());
    }

    public function testGetAndSetStartDate(): void
    {
        $uuid = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');
        $workEntry = new WorkEntry($uuid);
        $startDate = new \DateTime();

        $workEntry->setStartDate($startDate);

        self::assertSame($startDate, $workEntry->getStartDate());
    }

    public function testGetAndSetEndDate(): void
    {
        $uuid = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');
        $workEntry = new WorkEntry($uuid);
        $endDate = new \DateTime();

        $workEntry->setEndDate($endDate);

        self::assertSame($endDate, $workEntry->getEndDate());
    }

    public function testGetAndSetCreatedAt(): void
    {
        $uuid = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');
        $workEntry = new WorkEntry($uuid);
        $createdAt = new \DateTime();

        $workEntry->setCreatedAt($createdAt);

        self::assertSame($createdAt, $workEntry->getCreatedAt());
    }

    public function testGetAndSetUpdatedAt(): void
    {
        $uuid = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');
        $workEntry = new WorkEntry($uuid);
        $updatedAt = new \DateTime();

        $workEntry->setUpdatedAt($updatedAt);

        self::assertSame($updatedAt, $workEntry->getUpdatedAt());
    }

    public function testGetAndSetDeletedAt(): void
    {
        $uuid = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');
        $workEntry = new WorkEntry($uuid);
        $deletedAt = new \DateTime();

        $workEntry->setDeletedAt($deletedAt);

        self::assertSame($deletedAt, $workEntry->getDeletedAt());
    }
}
