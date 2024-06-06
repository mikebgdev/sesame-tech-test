<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Event;

use App\Shared\Domain\Bus\Event\Event;

final class WorkEntryEnded implements Event
{
    private string $workEntryId;
    private \DateTime $endedAt;

    public function __construct(string $workEntryId, \DateTime $endedAt)
    {
        $this->workEntryId = $workEntryId;
        $this->endedAt = $endedAt;
    }

    public function getEventName(): string
    {
        return 'work_entry.ended';
    }

    public function getPayload(): array
    {
        return [
            'workEntryId' => $this->workEntryId,
            'endedAt' => $this->endedAt->format('Y-m-d H:i:s'),
        ];
    }
}
