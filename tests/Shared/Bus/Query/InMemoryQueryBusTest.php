<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Shared\Bus\Query;

use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Infrastructure\Bus\Query\InMemoryQueryBus;
use PHPUnit\Framework\TestCase;

class InMemoryQueryBusTest extends TestCase
{
    public function testAskWithNoHandlerForMessageException(): void
    {
        $queryMock = $this->createMock(Query::class);

        $queryBus = new InMemoryQueryBus([]);

        $this->expectException(\InvalidArgumentException::class);
        $queryBus->ask($queryMock);
    }
}
