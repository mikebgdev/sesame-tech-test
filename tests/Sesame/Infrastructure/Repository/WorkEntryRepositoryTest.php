<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Infrastructure\Repository;

use App\Sesame\Infrastructure\Repository\WorkEntryRepository;
use App\Tests\Infrastructure\Repository\covers;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

#[covers(WorkEntryRepository::class)]
final class WorkEntryRepositoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $objectManager = $this->createMock(ObjectManager::class);

        $registry->method('getManagerForClass')->willReturn($objectManager);

        $repository = new WorkEntryRepository($registry);
        self::assertNotNull($repository);
    }
}
