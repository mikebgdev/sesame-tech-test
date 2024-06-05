<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Command;

use App\Shared\Domain\Bus\Command\Command;

final class UpdateUserCommand implements Command
{
    private string $id;
    private ?string $name;
    private ?string $email;
    private ?string $password;

    public function __construct(string $id, ?string $name, ?string $email, ?string $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
