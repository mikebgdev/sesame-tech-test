<?php

namespace App\Application\Service;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function getUserById(int $id): User
    {
        return $this->userRepository->findById($id);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    public function createUser(Request $request): User
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = new User(
            $username,
            $hashedPassword,
            $email
        );

        $this->userRepository->save($user);

        return $user;
    }

    public function updateUser(string $id, string $username, string $password, string $email): ?User
    {
        // TODO
    }

    public function deleteUser(string $id): void
    {
        // TODO
    }


}
