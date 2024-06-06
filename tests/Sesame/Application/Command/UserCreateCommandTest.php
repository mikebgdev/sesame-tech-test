<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Command;

use App\Sesame\Application\Command\UserCreateCommand;
use PHPUnit\Framework\TestCase;

final class UserCreateCommandTest extends TestCase
{
    public function testUserCreateCommand(): void
    {
        $command = new UserCreateCommand('John Doe', 'john@example.com', 'password123');

        self::assertEquals('John Doe', $command->getName());
        self::assertEquals('john@example.com', $command->getEmail());
        self::assertEquals('password123', $command->getPassword());
    }
}
