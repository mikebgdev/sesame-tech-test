<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Domain\Entity;

use App\Sesame\Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserTest extends TestCase
{
    public function testUserConstructor(): void
    {
        $name = 'John Doe';
        $email = 'john.doe@example.com';

        $user = new User($name, $email);

        self::assertSame($name, $user->getName());
        self::assertSame($email, $user->getEmail());
        self::assertNotEmpty($user->getCreatedAt());
        self::assertNotEmpty($user->getUpdatedAt());
        self::assertNull($user->getDeletedAt());
        self::assertSame('', $user->getPassword());
        self::assertContains('ROLE_USER', $user->getRoles());
    }

    public function testGetAndSetId(): void
    {
        $user = new User('John Doe', 'john.doe@example.com');
        $uuid = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');

        $user->setId($uuid);

        self::assertInstanceOf(UuidInterface::class, $user->getId());
        self::assertSame($uuid->toString(), $user->getId()->toString());
    }

    public function testGetAndSetName(): void
    {
        $user = new User('John Doe', 'john.doe@example.com');
        $newName = 'Jane Doe';

        $user->setName($newName);

        self::assertSame($newName, $user->getName());
    }

    public function testGetAndSetEmail(): void
    {
        $user = new User('John Doe', 'john.doe@example.com');
        $newEmail = 'jane.doe@example.com';

        $user->setEmail($newEmail);

        self::assertSame($newEmail, $user->getEmail());
    }

    public function testGetAndSetPassword(): void
    {
        $user = new User('John Doe', 'john.doe@example.com');
        $password = 'securepassword';

        $user->setPassword($password);

        self::assertSame($password, $user->getPassword());
    }

    public function testGetAndSetUpdatedAt(): void
    {
        $user = new User('John Doe', 'john.doe@example.com');
        $updatedAt = new \DateTime();

        $user->setUpdatedAt($updatedAt);

        self::assertSame($updatedAt, $user->getUpdatedAt());
    }

    public function testGetAndSetDeletedAt(): void
    {
        $user = new User('John Doe', 'john.doe@example.com');
        $deletedAt = new \DateTime();

        $user->setDeletedAt($deletedAt);

        self::assertSame($deletedAt, $user->getDeletedAt());
    }

    public function testEraseCredentials(): void
    {
        $user = new User('John Doe', 'john.doe@example.com');
        $user->eraseCredentials();

        self::assertTrue(true); // Just to ensure method can be called, as it does nothing.
    }

    public function testGetUserIdentifier(): void
    {
        $user = new User('John Doe', 'john.doe@example.com');

        self::assertSame('john.doe@example.com', $user->getUserIdentifier());
    }
}
