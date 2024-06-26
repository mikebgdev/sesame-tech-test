<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\UserUpdateCommand;
use App\Sesame\Application\Event\UserUpdated;
use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Bus\Event\EventBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserUpdateHandler implements CommandHandler
{
    private UserRepositoryInterface $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    private EventBus $eventBus;

    public function __construct(UserRepositoryInterface $userRepository, UserPasswordHasherInterface $userPasswordHasher, EventBus $eventBus)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->eventBus = $eventBus;
    }

    public function __invoke(UserUpdateCommand $command): ?User
    {
        $uuid = Uuid::fromString($command->getId());
        $user = $this->userRepository->getUserById($uuid);

        if (!$user) {
            throw new \RuntimeException('User not found');
        }

        $user->setName($command->getName() ?? $user->getName());
        $user->setEmail($command->getEmail() ?? $user->getEmail());

        if ($command->getPassword()) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $command->getPassword()));
        }

        $user->setUpdatedAt(new \DateTime());

        $this->userRepository->updateUser($user);

        $this->eventBus->publish(new UserUpdated($user->getId()->toString(), $user->getUpdatedAt()));

        return $user;
    }
}
