<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\UserDeleteCommand;
use App\Sesame\Application\Event\UserDeleted;
use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Bus\Event\EventBus;
use Ramsey\Uuid\Uuid;

final class UserDeleteHandler implements CommandHandler
{
    private UserRepositoryInterface $userRepository;
    private EventBus $eventBus;

    public function __construct(UserRepositoryInterface $userRepository, EventBus $eventBus)
    {
        $this->userRepository = $userRepository;
        $this->eventBus = $eventBus;
    }

    public function __invoke(UserDeleteCommand $command): ?User
    {
        $uuid = Uuid::fromString($command->getId());
        $user = $this->userRepository->getUserById($uuid);

        if (!$user) {
            throw new \RuntimeException('User not found');
        }

        $user->setUpdatedAt(new \DateTime());
        $user->setDeletedAt(new \DateTime());

        $this->userRepository->deleteUser($user);

        $this->eventBus->publish(new UserDeleted($user->getId()->toString(), $user->getDeletedAt()));

        return $user;
    }
}
