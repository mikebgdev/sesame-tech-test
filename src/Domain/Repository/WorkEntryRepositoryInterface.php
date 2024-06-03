<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

namespace App\Domain\Repository;

use App\Domain\Entity\WorkEntry;
use Ramsey\Uuid\UuidInterface;

interface WorkEntryRepositoryInterface
{
    public function getAllWorkEntryByUser(UuidInterface $userId): array;

    public function getWorkEntryById(UuidInterface $id): ?WorkEntry;

    public function startWorkEntry(WorkEntry $workEntry): void;

    public function endWorkEntry(WorkEntry $workEntry): void;

    public function deleteWorkEntry(WorkEntry $workEntry): void;
}
