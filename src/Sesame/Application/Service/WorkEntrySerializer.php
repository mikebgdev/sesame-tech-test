<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Service;

use App\Sesame\Domain\Entity\WorkEntry;

class WorkEntrySerializer
{
    public function serialize(WorkEntry $workEntry): array
    {
        return [
            'id' => $workEntry->getId()->toString(),
            'userId' => $workEntry->getUserId(),
            'startDate' => $workEntry->getStartDate()->format('c'),
            'endDate' => $workEntry->getEndDate()?->format('c'),
            'createdAt' => $workEntry->getCreatedAt()->format('c'),
            'updatedAt' => $workEntry->getUpdatedAt()->format('c'),
            'deletedAt' => $workEntry->getDeletedAt()?->format('c'),
        ];
    }

    public function serializeCollection(array $workEntrys): array
    {
        return \array_map([$this, 'serialize'], $workEntrys);
    }
}
