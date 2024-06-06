<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Event\Handler;

use App\Sesame\Application\Event\Handler\WorkEntryDeletedHandler;
use App\Sesame\Application\Event\WorkEntryDeleted;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class WorkEntryDeletedHandlerTest extends TestCase
{
    public function testInvokeLogsWorkEntryDeletedEvent(): void
    {
        $workEntryId = '1234-5678-9101-1121';
        $deletedAt = new \DateTime('2024-06-06 08:00:00');

        $event = new WorkEntryDeleted($workEntryId, $deletedAt);

        $logger = $this->createMock(LoggerInterface::class);

        $logger->expects(self::once())
            ->method('info')
            ->with('Work entry deleted: ', [
                'workEntryId' => $workEntryId,
                'deletedAt' => '2024-06-06 08:00:00',
            ]);

        $handler = new WorkEntryDeletedHandler($logger);

        $handler($event);
    }
}
