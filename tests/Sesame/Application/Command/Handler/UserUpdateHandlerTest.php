<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\Handler\UserUpdateHandler;
use App\Sesame\Application\Command\UserUpdateCommand;
use App\Sesame\Application\Event\UserUpdated;
use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Event\EventBus;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserUpdateHandlerTest extends TestCase
{
    public function testInvokeUpdateUserEvent(): void
    {
        $userId = '526586eb-d992-44e9-884b-4542bd3ec82b';
        $existingUser = new User('John Doe', 'john.doe@example.com');
        $existingUser->setId(Uuid::fromString($userId));

        $updatedName = 'John Updated';
        $updatedEmail = 'john.updated@example.com';
        $updatedPassword = 'updatedPassword';
        $updateCommand = new UserUpdateCommand($userId, $updatedName, $updatedEmail, $updatedPassword);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects(self::once())
            ->method('getUserById')
            ->with($userId)
            ->willReturn($existingUser);
        $userRepository->expects(self::once())
            ->method('updateUser')
            ->willReturnCallback(function (User $user) use ($updatedName, $updatedEmail) {
                $this->assertEquals($updatedName, $user->getName());
                $this->assertEquals($updatedEmail, $user->getEmail());
            });

        $userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $userPasswordHasher->expects(self::once())
            ->method('hashPassword')
            ->willReturn($updatedPassword);

        $eventBus = $this->createMock(EventBus::class);
        $eventBus->expects(self::once())
            ->method('publish')
            ->willReturnCallback(function ($event) use ($userId) {
                $this->assertInstanceOf(UserUpdated::class, $event);
                $this->assertEquals($userId, $event->getPayload()['userId']);
            });

        $handler = new UserUpdateHandler($userRepository, $userPasswordHasher, $eventBus);

        $updatedUser = $handler($updateCommand);

        self::assertInstanceOf(User::class, $updatedUser);
        self::assertEquals($updatedName, $updatedUser->getName());
        self::assertEquals($updatedEmail, $updatedUser->getEmail());
    }

    public function testInvokeDeleteUserEventException(): void
    {
        $this->expectException(\RuntimeException::class);

        $userId = '526586eb-d992-44e9-884b-4542bd3ec82b';
        $updatedName = 'John Updated';
        $updatedEmail = 'john.updated@example.com';
        $updatedPassword = 'updatedPassword';

        $updateCommand = new UserUpdateCommand($userId, $updatedName, $updatedEmail, $updatedPassword);

        $userRepository = $this->createMock(UserRepositoryInterface::class);

        $userRepository->method('getUserById')
            ->willReturn(null);

        $userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $eventBus = $this->createMock(EventBus::class);

        $handler = new UserUpdateHandler($userRepository, $userPasswordHasher, $eventBus);
        $handler($updateCommand);
    }
}
