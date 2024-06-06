<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\Handler\WorkEntryDeleteHandler;
use App\Sesame\Application\Command\WorkEntryDeleteCommand;
use App\Sesame\Application\Event\WorkEntryDeleted;
use App\Sesame\Domain\Entity\WorkEntry;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use App\Shared\Domain\Bus\Event\EventBus;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class WorkEntryDeleteHandlerTest extends TestCase
{
    public function testInvokeDeleteWorkEntryEvent(): void
    {
        $userId = '526586eb-d992-44e9-884b-4542bd3ec82a';
        $workEntryId = '526586eb-d992-44e9-884b-4542bd3ec82b';
        $existingWorkEntry = new WorkEntry(Uuid::fromString($userId));
        $existingWorkEntry->setId(Uuid::fromString($workEntryId));

        $deleteCommand = new WorkEntryDeleteCommand($workEntryId);

        $workEntryRepository = $this->createMock(WorkEntryRepositoryInterface::class);
        $workEntryRepository->expects(self::once())
            ->method('getWorkEntryById')
            ->with($workEntryId)
            ->willReturn($existingWorkEntry);
        $workEntryRepository->expects(self::once())
            ->method('deleteWorkEntry')
            ->with($existingWorkEntry);

        $eventBus = $this->createMock(EventBus::class);
        $eventBus->expects(self::once())
            ->method('publish')
            ->willReturnCallback(function ($event) use ($workEntryId) {
                $this->assertInstanceOf(WorkEntryDeleted::class, $event);
                $this->assertEquals($workEntryId, $event->getPayload()['workEntryId']);
            });

        $handler = new WorkEntryDeleteHandler($workEntryRepository, $eventBus);
        $deletedWorkEntry = $handler($deleteCommand);

        self::assertInstanceOf(WorkEntry::class, $deletedWorkEntry);
        self::assertEquals($workEntryId, $deletedWorkEntry->getId()->toString());
    }

    public function testInvokeDeleteWorkEntryEventException(): void
    {
        $this->expectException(\RuntimeException::class);

        $userId = '526586eb-d992-44e9-884b-4542bd3ec82a';
        $workEntryId = '526586eb-d992-44e9-884b-4542bd3ec82b';
        $existingWorkEntry = new WorkEntry(Uuid::fromString($userId));
        $existingWorkEntry->setId(Uuid::fromString($workEntryId));

        $deleteCommand = new WorkEntryDeleteCommand($workEntryId);

        $workEntryRepository = $this->createMock(WorkEntryRepositoryInterface::class);
        $workEntryRepository->expects(self::once())
            ->method('getWorkEntryById')
            ->with($workEntryId)
            ->willReturn(null);

        $eventBus = $this->createMock(EventBus::class);
        $handler = new WorkEntryDeleteHandler($workEntryRepository, $eventBus);
        $handler($deleteCommand);
    }
}
