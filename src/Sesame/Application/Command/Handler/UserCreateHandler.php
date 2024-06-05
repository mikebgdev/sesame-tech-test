<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Command\Handler;

use App\Sesame\Application\Command\UserCreateCommand;
use App\Sesame\Application\Event\WorkEntryStarted;
use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Bus\Event\EventBus;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserCreateHandler implements CommandHandler
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

    public function __invoke(UserCreateCommand $command): ?User
    {
        $user = new User($command->getName(), $command->getEmail());
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $command->getPassword());
        $user->setPassword($hashedPassword);

        $this->userRepository->saveUser($user);

        $this->eventBus->publish(new WorkEntryStarted($user->getId()->toString(), $user->getCreatedAt()));

        return $user;
    }
}
