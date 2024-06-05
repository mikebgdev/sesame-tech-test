<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Event\Handler;

use App\Sesame\Application\Event\UserCreated;
use App\Shared\Domain\Bus\Event\EventHandler;
use Psr\Log\LoggerInterface;

class UserCreatedHandler implements EventHandler
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(UserCreated $event): void
    {
        $this->logger->info('User created: ', $event->getPayload());
    }
}
