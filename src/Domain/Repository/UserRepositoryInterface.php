<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use Ramsey\Uuid\UuidInterface;

interface UserRepositoryInterface
{
    public function getAllUsers(): array;

    public function getUserById(UuidInterface $id): ?User;

    public function saveUser(User $user): void;

    public function updateUser(User $user): void;

    public function deleteUser(User $user): void;
}
