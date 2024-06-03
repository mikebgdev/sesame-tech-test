<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

namespace App\Application\Service;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserById(string $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function getAllUsers(): ?array
    {
        return $this->userRepository->getAllUsers();
    }

    public function createUser(Request $request): User
    {
        $data = \json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];

        $hashedPassword = \password_hash($password, \PASSWORD_DEFAULT);

        $user = new User(
            $username,
            $email,
            $hashedPassword
        );

        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @throws \JsonException
     */
    public function updateUser(Request $request, string $id): ?User
    {
        $uuid = Uuid::fromString($id);
        $user = $this->userRepository->findById($uuid);

        if (!$user) {
            return null;
        }

        $data = \json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        $user->setName($data['name'] ?? $user->getName());
        $user->setEmail($data['email'] ?? $user->getEmail());

        if (isset($data['password'])) {
            $user->setPassword(\password_hash($data['password'], \PASSWORD_BCRYPT));
        }

        $user->setUpdatedAt(new \DateTimeImmutable());
        $this->userRepository->update($user);

        return $user;
    }

    public function deleteUser(string $id): ?User
    {
        $uuid = Uuid::fromString($id);
        $user = $this->userRepository->findById($uuid);

        if (!$user) {
            return null;
        }

        $user->setUpdatedAt(new \DateTimeImmutable());
        $user->setDeletedAt(new \DateTimeImmutable());
        $this->userRepository->update($user);

        return $user;
    }
}
