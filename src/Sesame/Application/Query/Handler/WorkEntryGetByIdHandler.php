<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Query\Handler;

use App\Sesame\Application\Query\Response\WorkEntryGetByIdResponse;
use App\Sesame\Application\Query\WorkEntryGetByIdQuery;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class WorkEntryGetByIdHandler implements QueryHandler
{
    private WorkEntryRepositoryInterface $workEntryRepository;

    public function __construct(WorkEntryRepositoryInterface $workEntryRepository)
    {
        $this->workEntryRepository = $workEntryRepository;
    }

    public function __invoke(WorkEntryGetByIdQuery $query): WorkEntryGetByIdResponse
    {
        $workEntry = $this->workEntryRepository->getWorkEntryById($query->getId());

        return new WorkEntryGetByIdResponse(
            workEntry: $workEntry
        );
    }
}
