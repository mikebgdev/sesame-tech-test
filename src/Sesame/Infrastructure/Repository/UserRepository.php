<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

namespace App\Sesame\Infrastructure\Repository;

use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getAllUsers(): array
    {
        return $this->getEntityManager()->getRepository(User::class)->findAll();
    }

    public function getUserById(UuidInterface $id): ?User
    {
        return $this->getEntityManager()->getRepository(User::class)->find($id);
    }

    public function saveUser(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function updateUser(User $user): void
    {
        $this->getEntityManager()->flush();
    }

    public function deleteUser(User $user): void
    {
        $this->getEntityManager()->flush();
    }
}
