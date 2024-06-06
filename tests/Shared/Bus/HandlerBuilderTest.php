<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Shared\Bus;

use App\Shared\Infrastructure\Bus\HandlerBuilder;
use PHPUnit\Framework\TestCase;

final class HandlerBuilderTest extends TestCase
{
    public function testFromCallables(): void
    {
        $handler1 = new class() {
            public function __invoke(\stdClass $command): void
            {
            }
        };

        $handler2 = new class() {
            public function __invoke(\stdClass $command): void
            {
            }
        };

        $handler3 = new class() {
            public function __invoke(\Exception $command): void
            {
            }
        };

        $callables = [$handler1, $handler2, $handler3];

        $handlers = HandlerBuilder::fromCallables($callables);

        self::assertArrayHasKey(\stdClass::class, $handlers);
        self::assertArrayHasKey(\Exception::class, $handlers);

        self::assertCount(2, $handlers[\stdClass::class]);
        self::assertCount(1, $handlers[\Exception::class]);

        self::assertSame($handler1, $handlers[\stdClass::class][0]);
        self::assertSame($handler2, $handlers[\stdClass::class][1]);
        self::assertSame($handler3, $handlers[\Exception::class][0]);
    }

    public function testExtractFirstParam(): void
    {
        $handler = new class() {
            public function __invoke(\stdClass $command): void
            {
            }
        };

        $result = (new \ReflectionClass(HandlerBuilder::class))
            ->getMethod('extractFirstParam')
            ->invoke(null, $handler);

        self::assertSame(\stdClass::class, $result);
    }

    public function testExtractFirstParamWithInvalidCallable(): void
    {
        $handler = new class() {
            public function __invoke($param1, $param2): void
            {
            }
        };

        $result = (new \ReflectionClass(HandlerBuilder::class))
            ->getMethod('extractFirstParam')
            ->invoke(null, $handler);

        self::assertNull($result);
    }
}
