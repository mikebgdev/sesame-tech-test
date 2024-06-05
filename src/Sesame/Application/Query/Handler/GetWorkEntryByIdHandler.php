<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Query\Handler;

use App\Sesame\Application\Query\GetWorkEntryByIdQuery;
use App\Sesame\Application\Query\Response\GetWorkEntryByIdResponse;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class GetWorkEntryByIdHandler implements QueryHandler
{
    private WorkEntryRepositoryInterface $workEntryRepository;

    public function __construct(WorkEntryRepositoryInterface $workEntryRepository)
    {
        $this->workEntryRepository = $workEntryRepository;
    }

    public function __invoke(GetWorkEntryByIdQuery $query): GetWorkEntryByIdResponse
    {
        $workEntry = $this->workEntryRepository->getWorkEntryById($query->getId());

        return new GetWorkEntryByIdResponse(
            workEntry: $workEntry
        );
    }
}
