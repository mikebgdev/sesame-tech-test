<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\Handler\WorkEntryStartHandler;
use App\Sesame\Application\Command\WorkEntryStartCommand;
use App\Sesame\Application\Event\WorkEntryStarted;
use App\Sesame\Domain\Entity\WorkEntry;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use App\Shared\Domain\Bus\Event\EventBus;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class WorkEntryStartHandlerTest extends TestCase
{
    public function testInvokeStartWorkEntryEvent(): void
    {
        $userId = '526586eb-d992-44e9-884b-4542bd3ec82a';
        $workEntryId = '526586eb-d992-44e9-884b-4542bd3ec82b';

        $startCommand = new WorkEntryStartCommand(Uuid::fromString($userId));

        $workEntryRepository = $this->createMock(WorkEntryRepositoryInterface::class);
        $workEntryRepository->expects(self::once())
            ->method('startWorkEntry')
            ->willReturnCallback(function ($workEntry) use ($workEntryId) {
                $workEntry->setId(Uuid::fromString($workEntryId));
                $this->assertInstanceOf(WorkEntry::class, $workEntry);
            });

        $eventBus = $this->createMock(EventBus::class);
        $eventBus->expects(self::once())
            ->method('publish')
            ->willReturnCallback(function ($event) {
                $this->assertInstanceOf(WorkEntryStarted::class, $event);
            });

        $handler = new WorkEntryStartHandler($workEntryRepository, $eventBus);
        $startedWorkEntry = $handler($startCommand);

        self::assertInstanceOf(WorkEntry::class, $startedWorkEntry);
        self::assertEquals($userId, $startedWorkEntry->getUserId()->toString());
    }
}
