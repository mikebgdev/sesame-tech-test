<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\WorkEntry;
use App\Domain\Repository\WorkEntryRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class WorkEntryService
{
    private WorkEntryRepositoryInterface $workEntryRepository;

    /**
     * @param WorkEntryRepositoryInterface $workEntryRepository
     */
    public function __construct(WorkEntryRepositoryInterface $workEntryRepository)
    {
        $this->workEntryRepository = $workEntryRepository;
    }

    public function getAllWorkEntriesByUser(string $userId): array
    {
        try {
            $uuid = Uuid::fromString($userId);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException('Invalid UUID: '.$userId);
        }

        return $this->workEntryRepository->getAllWorkEntryByUser($uuid);
    }

    public function getWorkEntryById(string $id): ?WorkEntry
    {
        try {
            $uuid = Uuid::fromString($id);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException('Invalid User UUID: '.$id);
        }

        return $this->workEntryRepository->getWorkEntryById($uuid);
    }

    public function startWorkEntry(string $userId): ?WorkEntry
    {
        try {
            $userUuid = Uuid::fromString($userId);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException('Invalid UUID: '.$userId);
        }

        $workEntry = new WorkEntry(
            $userUuid
        );

        $this->workEntryRepository->startWorkEntry($workEntry);

        return $workEntry;
    }

    public function endWorkEntry(string $id): ?WorkEntry
    {
        try {
            $uuid = Uuid::fromString($id);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException('Invalid UUID: '.$id);
        }

        $workEntry = $this->workEntryRepository->getWorkEntryById($uuid);

        if (!$workEntry) {
            return null;
        }

        $workEntry->setUpdatedAt(new \DateTime());
        $workEntry->setEndDate(new \DateTime());

        $this->workEntryRepository->endWorkEntry($workEntry);

        return $workEntry;
    }

    public function deleteWorkEntry(string $id): ?WorkEntry
    {
        try {
            $uuid = Uuid::fromString($id);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException('Invalid UUID: '.$id);
        }

        $workEntry = $this->workEntryRepository->getWorkEntryById($uuid);

        if (!$workEntry) {
            return null;
        }

        $workEntry->setUpdatedAt(new \DateTime());
        $workEntry->setDeletedAt(new \DateTime());

        $this->workEntryRepository->deleteWorkEntry($workEntry);

        return $workEntry;
    }
}
