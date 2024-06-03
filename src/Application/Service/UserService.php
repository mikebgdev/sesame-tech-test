<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

namespace App\Application\Service;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class UserService
{
    private UserRepositoryInterface $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserRepositoryInterface $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function getUserById(string $id): ?User
    {
        try {
            $uuid = Uuid::fromString($id);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException('Invalid UUID: '.$id);
        }

        return $this->userRepository->getUserById($uuid);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    public function createUser(Request $request): User
    {
        try {
            $data = \json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new BadRequestHttpException('Invalid JSON data: '.$e->getMessage());
        }

        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];

        $user = new User(
            $name,
            $email
        );

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword);

        $this->userRepository->saveUser($user);

        return $user;
    }

    public function updateUser(Request $request, string $id): ?User
    {
        try {
            $uuid = Uuid::fromString($id);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException('Invalid UUID: '.$id);
        }

        try {
            $data = \json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new BadRequestHttpException('Invalid JSON data: '.$e->getMessage());
        }

        $user = $this->userRepository->getUserById($uuid);

        if (!$user) {
            return null;
        }

        $user->setName($data['name'] ?? $user->getName());
        $user->setEmail($data['email'] ?? $user->getEmail());

        if (isset($data['password'])) {
            $user->setPassword(\password_hash($data['password'], \PASSWORD_BCRYPT));
        }

        $user->setUpdatedAt(new \DateTime());

        $this->userRepository->updateUser($user);

        return $user;
    }

    public function deleteUser(string $id): ?User
    {
        try {
            $uuid = Uuid::fromString($id);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException('Invalid UUID: '.$id);
        }

        $user = $this->userRepository->getUserById($uuid);

        if (!$user) {
            return null;
        }

        $user->setUpdatedAt(new \DateTime());
        $user->setDeletedAt(new \DateTime());

        $this->userRepository->deleteUser($user);

        return $user;
    }
}
