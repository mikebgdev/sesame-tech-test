<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Query\Response;

use App\Sesame\Domain\Entity\WorkEntry;
use App\Shared\Domain\Bus\Query\Response;

final class GetAllWorkEntriesByUseResponse implements Response
{
    /** @var WorkEntry[] */
    private array $workEntries;

    public function __construct(array $workEntries)
    {
        $this->workEntries = $workEntries;
    }

    public function getWorkEntries(): array
    {
        return $this->workEntries;
    }
}
