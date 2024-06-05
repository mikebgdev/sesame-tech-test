<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Query\Handler;

use App\Sesame\Application\Query\GetUserByIdQuery;
use App\Sesame\Application\Query\Response\GetUserByIdResponse;
use App\Sesame\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class GetUserByIdHandler implements QueryHandler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(GetUserByIdQuery $query): GetUserByIdResponse
    {
        $user = $this->userRepository->getUserById($query->getId());

        return new GetUserByIdResponse(
            user: $user
        );
    }
}
