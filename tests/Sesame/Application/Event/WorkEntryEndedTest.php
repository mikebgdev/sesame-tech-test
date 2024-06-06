<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Event;

use App\Sesame\Application\Event\WorkEntryEnded;
use PHPUnit\Framework\TestCase;

final class WorkEntryEndedTest extends TestCase
{
    public function testGetEventName(): void
    {
        $workEntryId = '1234-5678-9101-1121';
        $updatedAt = new \DateTime('2024-06-06 12:00:00');

        $event = new WorkEntryEnded($workEntryId, $updatedAt);

        self::assertEquals('work_entry.ended', $event->getEventName());
    }

    public function testGetPayload(): void
    {
        $workEntryId = '1234-5678-9101-1121';
        $updatedAt = new \DateTime('2024-06-06 12:00:00');

        $event = new WorkEntryEnded($workEntryId, $updatedAt);

        $payload = $event->getPayload();
        self::assertIsArray($payload);
        self::assertArrayHasKey('workEntryId', $payload);
        self::assertArrayHasKey('endedAt', $payload);
        self::assertEquals($workEntryId, $payload['workEntryId']);
        self::assertEquals('2024-06-06 12:00:00', $payload['endedAt']);
    }
}
