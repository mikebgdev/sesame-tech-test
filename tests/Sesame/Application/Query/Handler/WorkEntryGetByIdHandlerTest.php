<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Query\Handler;

use App\Sesame\Application\Query\Handler\WorkEntryGetByIdHandler;
use App\Sesame\Application\Query\Response\WorkEntryGetByIdResponse;
use App\Sesame\Application\Query\WorkEntryGetByIdQuery;
use App\Sesame\Domain\Entity\WorkEntry;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class WorkEntryGetByIdHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $workEntryRepository = $this->createMock(WorkEntryRepositoryInterface::class);
        $expectedWorkEntryId = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');
        $expectedWorkEntry = new WorkEntry($expectedWorkEntryId);

        $workEntryRepository->expects(self::once())
            ->method('getWorkEntryById')
            ->willReturn($expectedWorkEntry);

        $handler = new WorkEntryGetByIdHandler($workEntryRepository);
        $workEntryId = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');
        $query = new WorkEntryGetByIdQuery($workEntryId);

        $response = $handler($query);

        self::assertInstanceOf(WorkEntryGetByIdResponse::class, $response);
        self::assertEquals($expectedWorkEntry, $response->getWorkEntry());
    }
}
