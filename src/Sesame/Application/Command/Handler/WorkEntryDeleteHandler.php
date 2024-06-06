<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\WorkEntryDeleteCommand;
use App\Sesame\Application\Event\WorkEntryDeleted;
use App\Sesame\Domain\Entity\WorkEntry;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Bus\Event\EventBus;
use Ramsey\Uuid\Uuid;

final class WorkEntryDeleteHandler implements CommandHandler
{
    private WorkEntryRepositoryInterface $workEntryRepository;
    private EventBus $eventBus;

    public function __construct(WorkEntryRepositoryInterface $workEntryRepository, EventBus $eventBus)
    {
        $this->workEntryRepository = $workEntryRepository;
        $this->eventBus = $eventBus;
    }

    public function __invoke(WorkEntryDeleteCommand $command): ?WorkEntry
    {
        $uuid = Uuid::fromString($command->getId());
        $workEntry = $this->workEntryRepository->getWorkEntryById($uuid);

        if (!$workEntry) {
            throw new \RuntimeException('Work entry not found');
        }

        $workEntry->setUpdatedAt(new \DateTime());
        $workEntry->setDeletedAt(new \DateTime());

        $this->workEntryRepository->deleteWorkEntry($workEntry);

        $this->eventBus->publish(new WorkEntryDeleted($workEntry->getId()->toString(), $workEntry->getDeletedAt()));

        return $workEntry;
    }
}
