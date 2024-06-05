<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\StartWorkEntryCommand;
use App\Sesame\Domain\Entity\WorkEntry;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandler;

final class StartWorkEntryHandler implements CommandHandler
{
    private WorkEntryRepositoryInterface $workEntryRepository;

    public function __construct(WorkEntryRepositoryInterface $workEntryRepository)
    {
        $this->workEntryRepository = $workEntryRepository;
    }

    public function __invoke(StartWorkEntryCommand $command): ?WorkEntry
    {
        $workEntry = new WorkEntry($command->getUserId());
        $this->workEntryRepository->startWorkEntry($workEntry);

        return $workEntry;
    }
}
