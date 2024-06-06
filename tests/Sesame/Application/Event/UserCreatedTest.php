<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Event;

use App\Sesame\Application\Event\UserCreated;
use PHPUnit\Framework\TestCase;

final class UserCreatedTest extends TestCase
{
    public function testGetEventName(): void
    {
        $userId = '1234-5678-9101-1121';
        $createdAt = new \DateTime('2024-06-06 12:00:00');

        $event = new UserCreated($userId, $createdAt);

        self::assertEquals('user.created', $event->getEventName());
    }

    public function testGetPayload(): void
    {
        $userId = '1234-5678-9101-1121';
        $createdAt = new \DateTime('2024-06-06 12:00:00');

        $event = new UserCreated($userId, $createdAt);

        $payload = $event->getPayload();
        self::assertIsArray($payload);
        self::assertArrayHasKey('userId', $payload);
        self::assertArrayHasKey('createdAt', $payload);
        self::assertEquals($userId, $payload['userId']);
        self::assertEquals('2024-06-06 12:00:00', $payload['createdAt']);
    }
}
