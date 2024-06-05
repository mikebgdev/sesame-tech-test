<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\EndWorkEntryCommand;
use App\Sesame\Domain\Entity\WorkEntry;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandler;
use Ramsey\Uuid\Uuid;

final class EndWorkEntryHandler implements CommandHandler
{
    private WorkEntryRepositoryInterface $workEntryRepository;

    public function __construct(WorkEntryRepositoryInterface $workEntryRepository)
    {
        $this->workEntryRepository = $workEntryRepository;
    }

    public function __invoke(EndWorkEntryCommand $command): ?WorkEntry
    {
        $uuid = Uuid::fromString($command->getId());
        $workEntry = $this->workEntryRepository->getWorkEntryById($uuid);

        if (!$workEntry) {
            return null;
        }

        $workEntry->setUpdatedAt(new \DateTime());
        $workEntry->setEndDate(new \DateTime());

        $this->workEntryRepository->endWorkEntry($workEntry);

        return $workEntry;
    }
}
