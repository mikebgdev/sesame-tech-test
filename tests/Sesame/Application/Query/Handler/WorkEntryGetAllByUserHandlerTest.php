<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Query\Handler;

use App\Sesame\Application\Query\Handler\WorkEntryGetAllByUserHandler;
use App\Sesame\Application\Query\Response\WorkEntryGetAllByUseResponse;
use App\Sesame\Application\Query\WorkEntryGetAllByUserQuery;
use App\Sesame\Domain\Repository\WorkEntryRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class WorkEntryGetAllByUserHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $workEntryRepository = $this->createMock(WorkEntryRepositoryInterface::class);

        $expectedWorkEntries = [
            Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec821'),
            Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec822'),
            Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec823'),
        ];

        $workEntryRepository->expects(self::once())
            ->method('getAllWorkEntryByUser')
            ->willReturn($expectedWorkEntries);

        $handler = new WorkEntryGetAllByUserHandler($workEntryRepository);
        $userId = Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b');
        $query = new WorkEntryGetAllByUserQuery($userId);

        $response = $handler($query);

        self::assertInstanceOf(WorkEntryGetAllByUseResponse::class, $response);
        self::assertEquals($expectedWorkEntries, $response->getWorkEntries());
    }
}
