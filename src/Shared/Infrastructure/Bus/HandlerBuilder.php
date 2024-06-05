<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

final class HandlerBuilder
{
    /**
     * @throws \ReflectionException
     */
    public static function fromCallables(iterable $callables): array
    {
        $callablesHandlers = [];

        foreach ($callables as $callable) {
            $envelop = self::extractFirstParam($callable);

            if (!\array_key_exists($envelop, $callablesHandlers)) {
                $callablesHandlers[self::extractFirstParam($callable)] = [];
            }

            $callablesHandlers[self::extractFirstParam($callable)][] = $callable;
        }

        return $callablesHandlers;
    }

    /**
     * @throws \ReflectionException
     */
    private static function extractFirstParam(object|string $class): string|null
    {
        $reflection = new \ReflectionClass($class);
        $method = $reflection->getMethod('__invoke');

        if (1 === $method->getNumberOfParameters()) {
            return $method->getParameters()[0]->getClass()?->getName();
        }

        return null;
    }
}
