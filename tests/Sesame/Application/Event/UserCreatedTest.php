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
        $createdAt = new \DateTime();
        $event = new UserCreated('user_id', $createdAt);

        self::assertEquals('user.created', $event->getEventName());
    }

    public function testGetPayload()
    {
        $createdAt = new \DateTime();
        $event = new UserCreated('user_id', $createdAt);

        $expectedPayload = [
            'userId' => 'user_id',
            'createdAt' => $createdAt->format('Y-m-d H:i:s'),
        ];

        self::assertEquals($expectedPayload, $event->getPayload());
    }
}
