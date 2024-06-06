<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Event;

use App\Sesame\Application\Event\WorkEntryDeleted;
use PHPUnit\Framework\TestCase;

final class WorkEntryDeletedTest extends TestCase
{
    public function testGetEventName(): void
    {
        $workEntryId = '5678-1234-9101-1121';
        $deletedAt = new \DateTime('2024-06-06 12:00:00');

        $event = new WorkEntryDeleted($workEntryId, $deletedAt);

        self::assertEquals('work_entry.deleted', $event->getEventName());
    }

    public function testGetPayload(): void
    {
        $workEntryId = '5678-1234-9101-1121';
        $deletedAt = new \DateTime('2024-06-06 12:00:00');

        $event = new WorkEntryDeleted($workEntryId, $deletedAt);

        $payload = $event->getPayload();
        self::assertIsArray($payload);
        self::assertArrayHasKey('workEntryId', $payload);
        self::assertArrayHasKey('deletedAt', $payload);
        self::assertEquals($workEntryId, $payload['workEntryId']);
        self::assertEquals('2024-06-06 12:00:00', $payload['deletedAt']);
    }
}
