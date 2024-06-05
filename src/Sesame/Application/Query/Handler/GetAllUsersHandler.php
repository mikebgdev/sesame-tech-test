<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Query\Handler;

use App\Sesame\Application\Query\GetAllUsersQuery;
use App\Sesame\Application\Query\Response\GetAllUsersResponse;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class GetAllUsersHandler implements QueryHandler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(GetAllUsersQuery $query): GetAllUsersResponse
    {
        $users = $this->userRepository->getAllUsers();

        return new GetAllUsersResponse(
            users: $users
        );
    }
}
