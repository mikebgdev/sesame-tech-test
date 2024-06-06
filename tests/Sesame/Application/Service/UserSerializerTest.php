<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Service;

use App\Sesame\Application\Service\UserSerializer;
use App\Sesame\Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class UserSerializerTest extends TestCase
{
    public function testSerializeUser(): void
    {
        $userId = '526586eb-d992-44e9-884b-4542bd3ec82a';
        $uuid = Uuid::fromString($userId);

        $user = new User('John Doe', 'john@example.com');
        $user->setId($uuid);

        $serializer = new UserSerializer();
        $serializedUser = $serializer->serialize($user);

        $expectedSerializedUser = [
            'id' => $userId,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'createdAt' => (new \DateTime())->format('c'),
            'updatedAt' => (new \DateTime())->format('c'),
            'deletedAt' => null,
        ];

        self::assertEquals($expectedSerializedUser, $serializedUser);
    }

    public function testSerializeCollection(): void
    {
        $user1 = new User('John Doe', 'john@example.com');
        $user1->setId(Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82a'));

        $user2 = new User('Jane Doe', 'jane@example.com');
        $user2->setId(Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b'));

        $users = [$user1, $user2];

        $serializer = new UserSerializer();
        $serializedUsers = $serializer->serializeCollection($users);

        self::assertCount(2, $serializedUsers);

        $expectedUser1 = [
            'id' => $user1->getId()->toString(),
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'createdAt' => (new \DateTime())->format('c'),
            'updatedAt' => (new \DateTime())->format('c'),
            'deletedAt' => null,
        ];
        self::assertEquals($expectedUser1, $serializedUsers[0]);

        $expectedUser2 = [
            'id' => $user2->getId()->toString(),
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'createdAt' => (new \DateTime())->format('c'),
            'updatedAt' => (new \DateTime())->format('c'),
            'deletedAt' => null,
        ];
        self::assertEquals($expectedUser2, $serializedUsers[1]);
    }
}
