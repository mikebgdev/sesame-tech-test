<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Command;

use App\Sesame\Application\Command\WorkEntryStartCommand;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class WorkEntryStartCommandTest extends TestCase
{
    public function testWorkEntryStartCommand(): void
    {
        $userId = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');

        $command = new WorkEntryStartCommand($userId);

        self::assertInstanceOf(UuidInterface::class, $command->getUserId());
        self::assertEquals($userId, $command->getUserId());
    }
}
