<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\User;

class UserSerializer
{
    public function serialize(User $user): array
    {
        return [
            'id' => $user->getId()->toString(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'createdAt' => $user->getCreatedAt()->format('c'),
            'updatedAt' => $user->getUpdatedAt()->format('c'),
            'deletedAt' => $user->getDeletedAt()?->format('c'),
        ];
    }

    public function serializeCollection(array $users): array
    {
        return \array_map([$this, 'serialize'], $users);
    }
}
