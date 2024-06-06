<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Event\Handler;

use App\Sesame\Application\Event\Handler\WorkEntryStartedHandler;
use App\Sesame\Application\Event\WorkEntryStarted;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class WorkEntryStartedHandlerTest extends TestCase
{
    public function testInvokeLogsWorkEntryStartedEvent(): void
    {
        $workEntryId = '1234-5678-9101-1121';
        $createdAt = new \DateTime('2024-06-06 08:00:00');

        $event = new WorkEntryStarted($workEntryId, $createdAt);

        $logger = $this->createMock(LoggerInterface::class);

        $logger->expects(self::once())
            ->method('info')
            ->with('Work entry started: ', [
                'workEntryId' => $workEntryId,
                'startedAt' => '2024-06-06 08:00:00',
            ]);

        $handler = new WorkEntryStartedHandler($logger);

        $handler($event);
    }
}
