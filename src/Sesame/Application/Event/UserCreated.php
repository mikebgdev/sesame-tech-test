<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Event;

use App\Shared\Domain\Bus\Event\Event;

final class UserCreated implements Event
{
    private string $userId;
    private \DateTime $createdAt;

    public function __construct(string $userId, \DateTime $createdAt)
    {
        $this->userId = $userId;
        $this->createdAt = $createdAt;
    }

    public function getEventName(): string
    {
        return 'user.created';
    }

    public function getPayload(): array
    {
        return [
            'userId' => $this->userId,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
