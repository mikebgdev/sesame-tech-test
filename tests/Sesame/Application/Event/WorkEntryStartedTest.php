<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Event;

use App\Sesame\Application\Event\WorkEntryStarted;
use PHPUnit\Framework\TestCase;

final class WorkEntryStartedTest extends TestCase
{
    public function testGetEventName(): void
    {
        $workEntryId = '1234-5678-9101-1121';
        $startedAt = new \DateTime('2024-06-06 08:00:00');

        $event = new WorkEntryStarted($workEntryId, $startedAt);

        self::assertEquals('work_entry.started', $event->getEventName());
    }

    public function testWorkEntryStartedEvent(): void
    {
        $workEntryId = '1234-5678-9101-1121';
        $startedAt = new \DateTime('2024-06-06 08:00:00');

        $event = new WorkEntryStarted($workEntryId, $startedAt);

        $payload = $event->getPayload();
        self::assertIsArray($payload);
        self::assertArrayHasKey('workEntryId', $payload);
        self::assertArrayHasKey('startedAt', $payload);
        self::assertEquals($workEntryId, $payload['workEntryId']);
        self::assertEquals('2024-06-06 08:00:00', $payload['startedAt']);
    }
}
