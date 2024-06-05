<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Event;

use App\Shared\Domain\Bus\Event\Event;

final class UserUpdated implements Event
{
    private string $userId;
    private \DateTime $updatedAt;

    public function __construct(string $userId, \DateTime $updatedAt)
    {
        $this->userId = $userId;
        $this->updatedAt = $updatedAt;
    }

    public function getEventName(): string
    {
        return 'user.updated';
    }

    public function getPayload(): array
    {
        return [
            'userId' => $this->userId,
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
