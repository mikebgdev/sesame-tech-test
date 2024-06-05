<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Command;

use App\Sesame\Application\Command\UserDeleteCommand;
use PHPUnit\Framework\TestCase;

final class UserDeleteCommandTest extends TestCase
{
    public function testUserDeleteCommand(): void
    {
        $command = new UserDeleteCommand('123abc');

        self::assertEquals('123abc', $command->getId());
    }
}
