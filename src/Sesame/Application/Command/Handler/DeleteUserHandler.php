<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\DeleteUserCommand;
use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandler;
use Ramsey\Uuid\Uuid;

final class DeleteUserHandler implements CommandHandler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(DeleteUserCommand $command): ?User
    {
        $uuid = Uuid::fromString($command->getId());
        $user = $this->userRepository->getUserById($uuid);

        if (!$user) {
            throw new \RuntimeException('User not found');
        }

        $user->setUpdatedAt(new \DateTime());
        $user->setDeletedAt(new \DateTime());

        $this->userRepository->deleteUser($user);

        return $user;
    }
}
