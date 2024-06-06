<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Event\Handler;

use App\Sesame\Application\Event\Handler\UserCreatedHandler;
use App\Sesame\Application\Event\UserCreated;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class UserCreatedHandlerTest extends TestCase
{
    public function testInvokeLogsUserCreatedEvent(): void
    {
        $userId = '1234-5678-9101-1121';
        $createdAt = new \DateTime('2024-06-06 08:00:00');

        $event = new UserCreated($userId, $createdAt);

        $logger = $this->createMock(LoggerInterface::class);

        $logger->expects(self::once())
            ->method('info')
            ->with('User created: ', [
                'userId' => $userId,
                'createdAt' => '2024-06-06 08:00:00',
            ]);

        $handler = new UserCreatedHandler($logger);

        $handler($event);
    }
}
