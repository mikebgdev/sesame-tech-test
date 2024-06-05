<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Query;

use App\Shared\Domain\Bus\Query\Query;
use Ramsey\Uuid\UuidInterface;

final class WorkEntryGetAllByUserQuery implements Query
{
    private UuidInterface $userId;

    public function __construct(UuidInterface $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }
}
