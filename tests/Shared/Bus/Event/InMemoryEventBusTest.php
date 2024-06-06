<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Shared\Bus\Event;

use App\Shared\Domain\Bus\Event\Event;
use App\Shared\Infrastructure\Bus\Event\InMemoryEventBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

final class InMemoryEventBusTest extends TestCase
{
    public function testPublishWithReflectionException(): void
    {
        $this->expectException(\ReflectionException::class);

        $event = $this->createMock(Event::class);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->method('dispatch')->willThrowException(new NoHandlerForMessageException());

        $eventBus = new InMemoryEventBus([$messageBus]);

        $eventBus->publish($event);
    }

    public function testDispatchWithInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $event = $this->createMock(Event::class);

        $handler1 = new class() {
            public function __invoke(\stdClass $event): void
            {
            }
        };

        $eventBus = new InMemoryEventBus([$handler1]);

        $eventBus->publish($event);
    }
}
