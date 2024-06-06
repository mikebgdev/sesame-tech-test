<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\Handler\UserDeleteHandler;
use App\Sesame\Application\Command\UserDeleteCommand;
use App\Sesame\Application\Event\UserDeleted;
use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Event\EventBus;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class UserDeleteHandlerTest extends TestCase
{
    public function testInvokeDeleteUserEvent(): void
    {
        $userId = '526586eb-d992-44e9-884b-4542bd3ec82b';
        $deletedAt = new \DateTime();

        $command = new UserDeleteCommand($userId);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $user = new User('John Doe', 'john.doe@example.com');
        $user->setId(Uuid::fromString($userId));

        $userRepository->method('getUserById')
            ->willReturn($user);
        $userRepository->expects(self::once())
            ->method('deleteUser')
            ->with(self::equalTo($user));

        $eventBus = $this->createMock(EventBus::class);
        $eventBus->expects(self::once())
            ->method('publish')
            ->with(self::callback(function (UserDeleted $event) use ($userId, $deletedAt) {
                return $event->getPayload()['userId'] === $userId
                    && $event->getPayload()['deletedAt'] === $deletedAt->format('Y-m-d H:i:s');
            }));

        $handler = new UserDeleteHandler($userRepository, $eventBus);
        $result = $handler($command);

        self::assertInstanceOf(User::class, $result);
        self::assertEquals($userId, $result->getId()->toString());
        self::assertEquals($deletedAt->format('Y-m-d H:i:s'), $result->getDeletedAt()->format('Y-m-d H:i:s'));
    }

    public function testInvokeDeleteUserEventException(): void
    {
        $this->expectException(\RuntimeException::class);

        $userId = '526586eb-d992-44e9-884b-4542bd3ec82b';

        $command = new UserDeleteCommand($userId);

        $userRepository = $this->createMock(UserRepositoryInterface::class);

        $userRepository->method('getUserById')
            ->willReturn(null);

        $eventBus = $this->createMock(EventBus::class);

        $handler = new UserDeleteHandler($userRepository, $eventBus);
        $handler($command);
    }
}
