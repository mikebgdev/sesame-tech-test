<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Infrastructure\Repository;

use App\Infrastructure\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

#[covers(UserRepository::class)]
final class UserRepositoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $objectManager = $this->createMock(ObjectManager::class);

        $registry->method('getManagerForClass')->willReturn($objectManager);

        $repository = new UserRepository($registry);
        self::assertNotNull($repository);
    }
}
