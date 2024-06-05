<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Query;

use App\Shared\Domain\Bus\Query\Query;
use Ramsey\Uuid\UuidInterface;

final class UserGetByIdQuery implements Query
{
    private UuidInterface $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
