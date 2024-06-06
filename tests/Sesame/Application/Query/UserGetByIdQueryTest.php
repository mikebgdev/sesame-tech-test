<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Query;

use App\Sesame\Application\Query\UserGetByIdQuery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserGetByIdQueryTest extends TestCase
{
    public function testUserGetByIdQuery(): void
    {
        $userId = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');

        $query = new UserGetByIdQuery($userId);

        self::assertInstanceOf(UuidInterface::class, $query->getId());
        self::assertEquals($userId, $query->getId());
    }
}
