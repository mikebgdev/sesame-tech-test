<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\CreateUserCommand;
use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateUserHandler implements CommandHandler
{
    private UserRepositoryInterface $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserRepositoryInterface $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function __invoke(CreateUserCommand $command): ?User
    {
        $user = new User($command->getName(), $command->getEmail());
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $command->getPassword());
        $user->setPassword($hashedPassword);

        $this->userRepository->saveUser($user);

        return $user;
    }
}
