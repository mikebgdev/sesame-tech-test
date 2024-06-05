<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Event;

use App\Shared\Domain\Bus\Event\Event;

final class WorkEntryDeleted implements Event
{
    private string $workEntryId;
    private \DateTime $deletedAt;

    public function __construct(string $workEntryId, \DateTime $deletedAt)
    {
        $this->workEntryId = $workEntryId;
        $this->deletedAt = $deletedAt;
    }

    public function getEventName(): string
    {
        return 'work_entry.deleted';
    }

    public function getPayload(): array
    {
        return [
            'workEntryId' => $this->workEntryId,
            'deletedAt' => $this->deletedAt->format('Y-m-d H:i:s'),
        ];
    }
}
