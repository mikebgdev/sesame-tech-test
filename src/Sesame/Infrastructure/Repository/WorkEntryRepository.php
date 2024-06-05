<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Infrastructure\Repository;

use App\Sesame\Domain\Entity\WorkEntry;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

final class WorkEntryRepository extends ServiceEntityRepository implements WorkEntryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkEntry::class);
    }

    public function getAllWorkEntryByUser(UuidInterface $userId): array
    {
        return $this->getEntityManager()->getRepository(WorkEntry::class)
            ->findBy(['userId' => $userId], ['startDate' => 'DESC']);
    }

    public function getWorkEntryById(UuidInterface $id): ?WorkEntry
    {
        return $this->getEntityManager()->getRepository(WorkEntry::class)->find($id);
    }

    public function startWorkEntry(WorkEntry $workEntry): void
    {
        $this->getEntityManager()->persist($workEntry);
        $this->getEntityManager()->flush();
    }

    public function endWorkEntry(WorkEntry $workEntry): void
    {
        $this->getEntityManager()->flush();
    }

    public function deleteWorkEntry(WorkEntry $workEntry): void
    {
        $this->getEntityManager()->flush();
    }
}
