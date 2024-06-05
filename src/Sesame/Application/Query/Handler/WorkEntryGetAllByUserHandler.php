<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Query\Handler;

use App\Sesame\Application\Query\Response\WorkEntryGetAllByUseResponse;
use App\Sesame\Application\Query\WorkEntryGetAllByUserQuery;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class WorkEntryGetAllByUserHandler implements QueryHandler
{
    private WorkEntryRepositoryInterface $workEntryRepository;

    public function __construct(WorkEntryRepositoryInterface $workEntryRepository)
    {
        $this->workEntryRepository = $workEntryRepository;
    }

    public function __invoke(WorkEntryGetAllByUserQuery $query): WorkEntryGetAllByUseResponse
    {
        $workEntries = $this->workEntryRepository->getAllWorkEntryByUser($query->getUserId());

        return new WorkEntryGetAllByUseResponse(
            workEntries: $workEntries
        );
    }
}
