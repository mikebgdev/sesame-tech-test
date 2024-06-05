<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Domain\Entity;

use App\Sesame\Domain\Entity\User;
use PHPUnit\Framework\TestCase;

#[covers(User::class)]
final class UserTest extends TestCase
{
    public function testUserIdentifier(): void
    {
        $user = new User('UserTest', 'user@test.com');

        $identifier = $user->getUserIdentifier();

        self::assertSame($user->getEmail(), $identifier);
    }
}
