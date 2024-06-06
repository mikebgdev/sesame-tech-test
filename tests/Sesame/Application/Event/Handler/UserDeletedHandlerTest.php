<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Event\Handler;

use App\Sesame\Application\Event\Handler\UserDeletedHandler;
use App\Sesame\Application\Event\UserDeleted;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class UserDeletedHandlerTest extends TestCase
{
    public function testInvokeLogsUserDeletedEvent(): void
    {
        $userId = '1234-5678-9101-1121';
        $deletedAt = new \DateTime('2024-06-06 08:00:00');

        $event = new UserDeleted($userId, $deletedAt);

        $logger = $this->createMock(LoggerInterface::class);

        $logger->expects(self::once())
            ->method('info')
            ->with('User deleted: ', [
                'userId' => $userId,
                'deletedAt' => '2024-06-06 08:00:00',
            ]);

        $handler = new UserDeletedHandler($logger);

        $handler($event);
    }
}
