<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Command;

use App\Sesame\Application\Command\WorkEntryDeleteCommand;
use PHPUnit\Framework\TestCase;

final class WorkEntryDeleteCommandTest extends TestCase
{
    public function testWorkEntryDeleteCommand(): void
    {
        $command = new WorkEntryDeleteCommand('123abc');

        self::assertEquals('123abc', $command->getId());
    }
}
