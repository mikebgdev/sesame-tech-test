<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Event;

use App\Shared\Domain\Bus\Event\Event;

final class UserDeleted implements Event
{
    private string $userId;
    private \DateTime $deletedAt;

    public function __construct(string $userId, \DateTime $deletedAt)
    {
        $this->userId = $userId;
        $this->deletedAt = $deletedAt;
    }

    public function getEventName(): string
    {
        return 'user.deleted';
    }

    public function getPayload(): array
    {
        return [
            'userId' => $this->userId,
            'deletedAt' => $this->deletedAt->format('Y-m-d H:i:s'),
        ];
    }
}
