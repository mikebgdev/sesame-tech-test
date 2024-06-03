<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests;

use App\Domain\Entity\User;
use PHPUnit\Framework\TestCase;


#[covers(User::class)]
class EmptyTest extends TestCase
{
    public function testEmpty(): void
    {
        // Este es un test vacío
        self::assertTrue(true);
    }
}
