<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Query\Handler;

use App\Sesame\Application\Query\Handler\UserGetByIdHandler;
use App\Sesame\Application\Query\Response\UserGetByIdResponse;
use App\Sesame\Application\Query\UserGetByIdQuery;
use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class UserGetByIdHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $expectedUser = new User('John Doe', 'john@example.com');

        $userRepository->expects(self::once())
            ->method('getUserById')
            ->willReturn($expectedUser);

        $handler = new UserGetByIdHandler($userRepository);
        $query = new UserGetByIdQuery(Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b'));

        $response = $handler($query);

        self::assertInstanceOf(UserGetByIdResponse::class, $response);
        self::assertEquals($expectedUser, $response->getUser());
    }
}
