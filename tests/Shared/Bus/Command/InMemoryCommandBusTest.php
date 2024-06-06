<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Shared\Bus\Command;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Infrastructure\Bus\Command\InMemoryCommandBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;

final class InMemoryCommandBusTest extends TestCase
{
    public function testPublishWithReflectionException(): void
    {
        $this->expectException(\ReflectionException::class);

        $command = $this->createMock(Command::class);
        $messageBus = $this->createMock(MessageBusInterface::class);

        $commandBus = new InMemoryCommandBus([$messageBus]);

        $commandBus->dispatch($command);
    }
}
