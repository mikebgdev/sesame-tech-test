<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Event;

use App\Shared\Domain\Bus\Event\Event;

final class WorkEntryStarted implements Event
{
    private string $workEntryId;
    private \DateTime $startedAt;

    public function __construct(string $workEntryId, \DateTime $startedAt)
    {
        $this->workEntryId = $workEntryId;
        $this->startedAt = $startedAt;
    }

    public function getEventName(): string
    {
        return 'work_entry.started';
    }

    public function getPayload(): array
    {
        return [
            'workEntryId' => $this->workEntryId,
            'startedAt' => $this->startedAt->format('Y-m-d H:i:s'),
        ];
    }
}
