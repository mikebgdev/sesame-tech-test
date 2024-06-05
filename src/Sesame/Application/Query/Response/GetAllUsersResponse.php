<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Query\Response;

use App\Sesame\Domain\Entity\User;
use App\Shared\Domain\Bus\Query\Response;

final class GetAllUsersResponse implements Response
{
    /** @var User[] */
    private array $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function getUsers(): array
    {
        return $this->users;
    }
}
