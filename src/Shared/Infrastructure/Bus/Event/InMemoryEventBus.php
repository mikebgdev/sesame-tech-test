<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\Event;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Infrastructure\Bus\HandlerBuilder;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class InMemoryEventBus implements EventBus
{
    private MessageBus $bus;

    /**
     * @throws \ReflectionException
     */
    public function __construct(iterable $eventHandlers)
    {
        $this->bus = new MessageBus([
            new HandleMessageMiddleware(
                new HandlersLocator(
                    HandlerBuilder::fromCallables($eventHandlers),
                ),
            ),
        ]);
    }

    public function publish(Event $event): void
    {
        try {
            $this->bus->dispatch($event);
        } catch (NoHandlerForMessageException $e) {
            throw new \InvalidArgumentException(\sprintf('The event has not a valid handler: %s', $event::class));
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious();
        }
    }
}
