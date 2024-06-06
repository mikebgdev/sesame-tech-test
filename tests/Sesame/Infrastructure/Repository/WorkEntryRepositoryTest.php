<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Infrastructure\Repository;

use App\Sesame\Domain\Entity\WorkEntry;
use App\Sesame\Infrastructure\Repository\WorkEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class WorkEntryRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(EntityRepository::class);

        // Mock the ClassMetadata
        $this->classMetadata = $this->createMock(ClassMetadata::class);
        $this->classMetadata->name = WorkEntry::class;

        $this->entityManager->expects(self::any())
            ->method('getRepository')
            ->willReturn($this->repository);

        $this->entityManager->expects(self::any())
            ->method('getClassMetadata')
            ->willReturn($this->classMetadata);

        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $managerRegistry->expects(self::any())
            ->method('getManagerForClass')
            ->willReturn($this->entityManager);

        $this->workEntryRepository = new WorkEntryRepository($managerRegistry);
    }

    public function testGetAllWorkEntryByUser(): void
    {
        $userId = Uuid::uuid4();
        $workEntry = $this->createMock(WorkEntry::class);
        $workEntries = [$workEntry];

        $this->repository->expects(self::once())
            ->method('findBy')
            ->with(['userId' => $userId], ['startDate' => 'DESC'])
            ->willReturn($workEntries);

        $result = $this->workEntryRepository->getAllWorkEntryByUser($userId);

        self::assertSame($workEntries, $result);
    }

    public function testGetWorkEntryById(): void
    {
        $entryId = Uuid::uuid4();
        $workEntry = $this->createMock(WorkEntry::class);

        $this->repository->expects(self::once())
            ->method('find')
            ->with($entryId)
            ->willReturn($workEntry);

        $result = $this->workEntryRepository->getWorkEntryById($entryId);

        self::assertSame($workEntry, $result);
    }

    public function testStartWorkEntry(): void
    {
        $workEntry = $this->createMock(WorkEntry::class);

        $this->entityManager->expects(self::once())
            ->method('persist')
            ->with($workEntry);

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->workEntryRepository->startWorkEntry($workEntry);
    }

    public function testEndWorkEntry(): void
    {
        $workEntry = $this->createMock(WorkEntry::class);

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->workEntryRepository->endWorkEntry($workEntry);
    }

    public function testDeleteWorkEntry(): void
    {
        $workEntry = $this->createMock(WorkEntry::class);

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->workEntryRepository->deleteWorkEntry($workEntry);
    }
}
