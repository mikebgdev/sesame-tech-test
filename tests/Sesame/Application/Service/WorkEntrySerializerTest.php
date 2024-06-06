<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Sesame\Application\Service;

use App\Sesame\Application\Service\WorkEntrySerializer;
use App\Sesame\Domain\Entity\WorkEntry;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class WorkEntrySerializerTest extends TestCase
{
    public function testSerializeWorkEntry(): void
    {
        $workEntryId = '526586eb-d992-44e9-884b-4542bd3ec82b';
        $userId = '526586eb-d992-44e9-884b-4542bd3ec82a';

        $workEntry = new WorkEntry(Uuid::fromString($userId));
        $workEntry->setId(Uuid::fromString($workEntryId));

        $serializer = new WorkEntrySerializer();
        $serializedWorkEntry = $serializer->serialize($workEntry);

        $expectedSerializedWorkEntry = [
            'id' => $workEntryId,
            'userId' => $userId,
            'startDate' => (new \DateTime())->format('c'),
            'endDate' => null,
            'createdAt' => (new \DateTime())->format('c'),
            'updatedAt' => (new \DateTime())->format('c'),
            'deletedAt' => null,
        ];

        self::assertEquals($expectedSerializedWorkEntry, $serializedWorkEntry);
    }

    public function testSerializeCollection()
    {
        $workEntryId1 = '526586eb-d992-44e9-884b-4542bd3ec82b';
        $userId1 = '526586eb-d992-44e9-884b-4542bd3ec82a';

        $workEntry1 = new WorkEntry(Uuid::fromString($userId1));
        $workEntry1->setId(Uuid::fromString($workEntryId1));

        $workEntryId2 = '526586eb-d992-44e9-884b-4542bd3ec82c';
        $userId2 = '526586eb-d992-44e9-884b-4542bd3ec82d';

        $workEntry2 = new WorkEntry(Uuid::fromString($userId2));
        $workEntry2->setId(Uuid::fromString($workEntryId2));

        $workEntries = [$workEntry1, $workEntry2];

        $serializer = new WorkEntrySerializer();
        $serializedWorkEntries = $serializer->serializeCollection($workEntries);

        self::assertCount(2, $serializedWorkEntries);

        $expectedWorkEntry1 = [
            'id' => $workEntry1->getId()->toString(),
            'userId' => $workEntry1->getUserId()->toString(),
            'startDate' => (new \DateTime())->format('c'),
            'endDate' => null,
            'createdAt' => (new \DateTime())->format('c'),
            'updatedAt' => (new \DateTime())->format('c'),
            'deletedAt' => null,
        ];
        self::assertEquals($expectedWorkEntry1, $serializedWorkEntries[0]);

        $expectedWorkEntry2 = [
            'id' => $workEntry2->getId()->toString(),
            'userId' => $workEntry2->getUserId()->toString(),
            'startDate' => (new \DateTime())->format('c'),
            'endDate' => null,
            'createdAt' => (new \DateTime())->format('c'),
            'updatedAt' => (new \DateTime())->format('c'),
            'deletedAt' => null,
        ];
        self::assertEquals($expectedWorkEntry2, $serializedWorkEntries[1]);
    }
}
