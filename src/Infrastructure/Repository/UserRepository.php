<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Entity\WorkEntry;
use App\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkEntry::class);
    }

    public function getAllUsers(): array
    {
        return $this->getEntityManager()->getRepository(User::class)->findAll();
    }

    public function findById(string $id): ?User
    {
        return $this->getEntityManager()->getRepository(User::class)->find($id);
    }

    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function update(User $user): void
    {
        // TODO: Implement update() method.
    }


    public function delete(User $user): void
    {
        // TODO: Implement delete() method.
    }


}
