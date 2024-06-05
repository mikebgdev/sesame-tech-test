<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Sesame\Application\Query\Response;

use App\Sesame\Domain\Entity\WorkEntry;
use App\Shared\Domain\Bus\Query\Response;

final class GetWorkEntryByIdResponse implements Response
{
    private ?WorkEntry $workEntry;

    public function __construct(?WorkEntry $workEntry)
    {
        $this->workEntry = $workEntry;
    }

    public function getWorkEntry(): ?WorkEntry
    {
        return $this->workEntry;
    }
}
