<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Query\Handler;

use App\Sesame\Application\Query\GetAllWorkEntriesByUserQuery;
use App\Sesame\Application\Query\Response\GetAllWorkEntriesByUseResponse;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class GetAllWorkEntriesByUserHandler implements QueryHandler
{
    private WorkEntryRepositoryInterface $workEntryRepository;

    public function __construct(WorkEntryRepositoryInterface $workEntryRepository)
    {
        $this->workEntryRepository = $workEntryRepository;
    }

    public function __invoke(GetAllWorkEntriesByUserQuery $query): GetAllWorkEntriesByUseResponse
    {
        $workEntries = $this->workEntryRepository->getAllWorkEntryByUser($query->getUserId());

        return new GetAllWorkEntriesByUseResponse(
            workEntries: $workEntries
        );
    }
}
