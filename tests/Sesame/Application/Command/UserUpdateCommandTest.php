<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Command;

use App\Sesame\Application\Command\UserUpdateCommand;
use PHPUnit\Framework\TestCase;

final class UserUpdateCommandTest extends TestCase
{
    public function testUserUpdateCommand(): void
    {
        $command = new UserUpdateCommand('123abc', 'New Name', 'newemail@example.com', 'newpassword');

        self::assertEquals('123abc', $command->getId());
        self::assertEquals('New Name', $command->getName());
        self::assertEquals('newemail@example.com', $command->getEmail());
        self::assertEquals('newpassword', $command->getPassword());
    }

    public function testUserUpdateCommandWithNullValues(): void
    {
        $command = new UserUpdateCommand('123abc', null, null, null);

        self::assertEquals('123abc', $command->getId());
        self::assertNull($command->getName());
        self::assertNull($command->getEmail());
        self::assertNull($command->getPassword());
    }
}
