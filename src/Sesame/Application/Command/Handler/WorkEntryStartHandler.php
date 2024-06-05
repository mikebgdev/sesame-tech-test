<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\WorkEntryStartCommand;
use App\Sesame\Application\Event\WorkEntryStarted;
use App\Sesame\Domain\Entity\WorkEntry;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Bus\Event\EventBus;

final class WorkEntryStartHandler implements CommandHandler
{
    private WorkEntryRepositoryInterface $workEntryRepository;
    private EventBus $eventBus;

    public function __construct(WorkEntryRepositoryInterface $workEntryRepository, EventBus $eventBus)
    {
        $this->workEntryRepository = $workEntryRepository;
        $this->eventBus = $eventBus;
    }

    public function __invoke(WorkEntryStartCommand $command): ?WorkEntry
    {
        $workEntry = new WorkEntry($command->getUserId());
        $this->workEntryRepository->startWorkEntry($workEntry);

        $this->eventBus->publish(new WorkEntryStarted($workEntry->getId()->toString(), $workEntry->getCreatedAt()));

        return $workEntry;
    }
}
