<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Query\Handler;

use App\Sesame\Application\Query\Handler\UserGetAllHandler;
use App\Sesame\Application\Query\Response\UserGetAllResponse;
use App\Sesame\Application\Query\UserGetAllQuery;
use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class UserGetAllHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);

        $expectedUsers = [new User('John Doe', 'john@example.com')];

        $userRepository->expects(self::once())
            ->method('getAllUsers')
            ->willReturn($expectedUsers);

        $handler = new UserGetAllHandler($userRepository);
        $query = new UserGetAllQuery();

        $response = $handler($query);

        self::assertInstanceOf(UserGetAllResponse::class, $response);
        self::assertEquals($expectedUsers, $response->getUsers());
    }
}
