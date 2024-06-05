<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Command;

use App\Sesame\Application\Command\WorkEntryEndCommand;
use PHPUnit\Framework\TestCase;

final class WorkEntryEndCommandTest extends TestCase
{
    public function testWorkEntryEndCommand(): void
    {
        $command = new WorkEntryEndCommand('123abc');

        self::assertEquals('123abc', $command->getId());
    }
}
