<?php

namespace App\Domain\Repository;


use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function findAll(): array;
    public function findById(string $id): ?User;
    public function save(User $user): void;
    public function update(User $user): void;
    public function delete(User $user): void;
}
