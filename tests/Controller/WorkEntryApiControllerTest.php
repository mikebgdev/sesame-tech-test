<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\WorkEntryApiController;
use App\Sesame\Application\Query\Response\WorkEntryGetAllByUseResponse;
use App\Sesame\Application\Query\Response\WorkEntryGetByIdResponse;
use App\Sesame\Application\Service\WorkEntrySerializer;
use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Entity\WorkEntry;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class WorkEntryApiControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private CommandBus $commandBus;
    private WorkEntrySerializer $workEntrySerializer;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->workEntrySerializer = $this->createMock(WorkEntrySerializer::class);
    }

    public function testGetAllWorkEntriesByUserReturnsJsonResponseOk(): void
    {
        $this->queryBus->expects(self::once())
            ->method('ask')
            ->willReturn(new WorkEntryGetAllByUseResponse([$this->mockWorkEntryJson()]));

        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerWithUser());

        $response = $controller->getAllWorkEntriesByUser();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetAllWorkEntriesByUserReturnsJsonResponseNotAuthorized(): void
    {
        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerNullUser());

        $response = $controller->getAllWorkEntriesByUser();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testGetAllWorkEntriesByUserReturnsJsonResponseNotFound(): void
    {
        $this->queryBus->expects(self::once())
            ->method('ask')
            ->willReturn(new WorkEntryGetAllByUseResponse([]));

        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerWithUser());

        $response = $controller->getAllWorkEntriesByUser();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testFetWorkEntryByIdReturnsJsonResponseOk(): void
    {
        $this->queryBus->expects(self::once())
            ->method('ask')
            ->willReturn(new WorkEntryGetByIdResponse($this->mockWorkEntry()));

        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerWithUser());

        $response = $controller->getWorkEntryById('526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testFetWorkEntryByIdReturnsJsonResponseBadRequest(): void
    {
        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerWithUser());

        $response = $controller->getWorkEntryById('526586eb-d992-44e9-884b-4542bd3ec82333b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testFetWorkEntryByIdReturnsJsonResponseNotAuthorized(): void
    {
        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerNullUser());

        $response = $controller->getWorkEntryById('526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testFetWorkEntryByIdReturnsJsonResponseNotFound(): void
    {
        $this->queryBus->expects(self::once())
            ->method('ask')
            ->willReturn(new WorkEntryGetByIdResponse(null));

        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerWithUser());

        $response = $controller->getWorkEntryById('526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testStartWorkEntryReturnsJsonResponseCreated(): void
    {
        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerWithUser());

        $response = $controller->startWorkEntry();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testStartWorkEntryReturnsJsonResponseNotAuthorized(): void
    {
        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerNullUser());

        $response = $controller->startWorkEntry();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testEndWorkEntryReturnsJsonResponseOk(): void
    {
        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerWithUser());

        $response = $controller->endWorkEntry('526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testEndWorkEntryReturnsJsonResponseBadRequest(): void
    {
        $this->commandBus->expects(self::once())
            ->method('dispatch')
            ->willThrowException(new InvalidUuidStringException());

        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerWithUser());

        $response = $controller->endWorkEntry('526586eb-d992-44e9-884b-4542bd3ecasasd82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testEndWorkEntryReturnsJsonResponseNotAuthorized(): void
    {
        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerNullUser());

        $response = $controller->endWorkEntry('526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testEndWorkEntryReturnsJsonResponseNotFound(): void
    {
        $this->commandBus->expects(self::once())
            ->method('dispatch')
            ->willThrowException(new \RuntimeException());

        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerWithUser());

        $response = $controller->endWorkEntry('526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testDeleteWorkEntryReturnsJsonResponseNotContent(): void
    {
        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerWithUser());

        $response = $controller->deleteWorkEntry('526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteWorkEntryReturnsJsonResponseBadRequest(): void
    {
        $this->commandBus->expects(self::once())
            ->method('dispatch')
            ->willThrowException(new InvalidUuidStringException());

        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerWithUser());

        $response = $controller->deleteWorkEntry('526586eb-d992-44e9-884b-4542bd3ecasasd82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testDeleteWorkEntryReturnsJsonResponseNotAuthorized(): void
    {
        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerNullUser());

        $response = $controller->deleteWorkEntry('526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testDeleteWorkEntryReturnsJsonResponseNotFound(): void
    {
        $this->commandBus->expects(self::once())
            ->method('dispatch')
            ->willThrowException(new \RuntimeException());

        $controller = new WorkEntryApiController($this->queryBus, $this->commandBus, $this->workEntrySerializer);
        $controller->setContainer($this->mockContainerWithUser());

        $response = $controller->deleteWorkEntry('526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function mockContainerWithUser(): Container
    {
        $user = $this->mockUser();
        $user->setId(Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b'));

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);

        $container = $this->createMock(Container::class);
        $container->method('has')->with('security.token_storage')->willReturn(true);
        $container->method('get')->with('security.token_storage')->willReturn($tokenStorage);

        return $container;
    }

    public function mockContainerNullUser(): Container
    {
        $user = null;

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);

        $container = $this->createMock(Container::class);
        $container->method('has')->with('security.token_storage')->willReturn(true);
        $container->method('get')->with('security.token_storage')->willReturn($tokenStorage);

        return $container;
    }

    public function mockUser(): User
    {
        return new User(
            'nombre',
            'email',
        );
    }

    public function mockWorkEntry(): WorkEntry
    {
        return new WorkEntry(
            Uuid::fromString('526586eb-d992-44e9-884b-4542bd3ec82b')
        );
    }

    public function mockWorkEntryJson(): string
    {
        return '{
            "id": "bf3ccf74-622e-4f6e-a31e-7d0b8e73728a",
            "userId": "526586eb-d992-44e9-884b-4542bd3ec82b",
            "startDate": "2024-06-05T20:34:18+00:00",
            "endDate": null,
            "createdAt": "2024-06-05T20:34:18+00:00",
            "updatedAt": "2024-06-05T20:34:18+00:00",
            "deletedAt": null
          }';
    }
}
