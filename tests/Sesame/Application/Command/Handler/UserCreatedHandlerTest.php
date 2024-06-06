<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\Handler\UserCreateHandler;
use App\Sesame\Application\Command\UserCreateCommand;
use App\Sesame\Application\Event\UserCreated;
use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Event\EventBus;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserCreatedHandlerTest extends TestCase
{
    public function testInvokeCreateUserEvent(): void
    {
        $name = 'John Doe';
        $email = 'john.doe@example.com';
        $password = 'password123';
        $hashedPassword = 'hashedPassword123';
        $userId = '526586eb-d992-44e9-884b-4542bd3ec82b';
        $createdAt = new \DateTimeImmutable();

        $command = new UserCreateCommand($name, $email, $password);

        $userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $userPasswordHasher->method('hashPassword')
            ->willReturn($hashedPassword);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects(self::once())
            ->method('saveUser')
            ->with(self::callback(function (User $user) use ($hashedPassword, $userId) {
                $user->setId(Uuid::fromString($userId));

                return $user->getPassword() === $hashedPassword;
            }));

        $eventBus = $this->createMock(EventBus::class);
        $eventBus->expects(self::once())
            ->method('publish')
            ->with(self::callback(function (UserCreated $event) use ($userId, $createdAt) {
                return $event->getPayload()['userId'] === $userId
                    && $event->getPayload()['createdAt'] === $createdAt->format('Y-m-d H:i:s');
            }));

        $handler = new UserCreateHandler($userRepository, $userPasswordHasher, $eventBus);
        $result = $handler($command);

        self::assertInstanceOf(User::class, $result);
        self::assertEquals($name, $result->getName());
        self::assertEquals($email, $result->getEmail());
        self::assertEquals($hashedPassword, $result->getPassword());
    }
}
