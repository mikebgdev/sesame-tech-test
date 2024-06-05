<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Query\Handler;

use App\Sesame\Application\Query\Response\UserGetAllResponse;
use App\Sesame\Application\Query\UserGetAllQuery;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class UserGetAllHandler implements QueryHandler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(UserGetAllQuery $query): UserGetAllResponse
    {
        $users = $this->userRepository->getAllUsers();

        return new UserGetAllResponse(
            users: $users
        );
    }
}
