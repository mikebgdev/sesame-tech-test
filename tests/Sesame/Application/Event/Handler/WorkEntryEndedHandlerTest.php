<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Event\Handler;

use App\Sesame\Application\Event\Handler\WorkEntryEndedHandler;
use App\Sesame\Application\Event\WorkEntryEnded;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class WorkEntryEndedHandlerTest extends TestCase
{
    public function testInvokeLogsWorkEntryEndedEvent(): void
    {
        $workEntryId = '1234-5678-9101-1121';
        $endedAt = new \DateTime('2024-06-06 08:00:00');

        $event = new WorkEntryEnded($workEntryId, $endedAt);

        $logger = $this->createMock(LoggerInterface::class);

        $logger->expects(self::once())
            ->method('info')
            ->with('Work entry ended: ', [
                'workEntryId' => $workEntryId,
                'endedAt' => '2024-06-06 08:00:00',
            ]);

        $handler = new WorkEntryEndedHandler($logger);

        $handler($event);
    }
}
