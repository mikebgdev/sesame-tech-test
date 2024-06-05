<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Infrastructure\Repository;

use App\Sesame\Domain\Entity\User;
use App\Sesame\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class UserRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(EntityRepository::class);

        $this->classMetadata = $this->createMock(ClassMetadata::class);
        $this->classMetadata->name = User::class;

        $this->entityManager->expects(self::any())
            ->method('getRepository')
            ->willReturn($this->repository);

        $this->entityManager->expects(self::any())
            ->method('getClassMetadata')
            ->willReturn($this->classMetadata);

        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $managerRegistry->expects(self::any())
            ->method('getManagerForClass')
            ->willReturn($this->entityManager);

        $this->userRepository = new UserRepository($managerRegistry);
    }

    public function testGetAllUsers(): void
    {
        $user = $this->createMock(User::class);
        $users = [$user];

        $this->repository->expects(self::once())
            ->method('findAll')
            ->willReturn($users);

        $result = $this->userRepository->getAllUsers();

        self::assertSame($users, $result);
    }

    public function testGetUserById(): void
    {
        $userId = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');
        $user = $this->createMock(User::class);

        $this->repository->expects(self::once())
            ->method('find')
            ->with($userId)
            ->willReturn($user);

        $result = $this->userRepository->getUserById($userId);

        self::assertSame($user, $result);
    }

    public function testSaveUser(): void
    {
        $user = $this->createMock(User::class);

        $this->entityManager->expects(self::once())
            ->method('persist')
            ->with($user);

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->userRepository->saveUser($user);
    }

    public function testUpdateUser(): void
    {
        $user = $this->createMock(User::class);

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->userRepository->updateUser($user);
    }

    public function testDeleteUser(): void
    {
        $user = $this->createMock(User::class);

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->userRepository->deleteUser($user);
    }
}
