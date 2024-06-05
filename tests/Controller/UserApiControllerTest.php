<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\UserApiController;
use App\Sesame\Application\Query\Response\UserGetAllResponse;
use App\Sesame\Application\Query\Response\UserGetByIdResponse;
use App\Sesame\Application\Service\UserSerializer;
use App\Sesame\Domain\Entity\User;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class UserApiControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private CommandBus $commandBus;
    private UserSerializer $userSerializer;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->userSerializer = $this->createMock(UserSerializer::class);
    }

    public function testGetAllUserReturnsJsonResponseOk(): void
    {
        $this->queryBus->expects(self::once())
            ->method('ask')
            ->willReturn(new UserGetAllResponse([$this->mockUser()]));

        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->getAllUsers();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetAllUserReturnsJsonResponseNotFound(): void
    {
        $this->queryBus->expects(self::once())
            ->method('ask')
            ->willReturn(new UserGetAllResponse([]));

        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->getAllUsers();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testGetUserByIdReturnsJsonResponseOk(): void
    {
        $this->queryBus->expects(self::once())
            ->method('ask')
            ->willReturn(new UserGetByIdResponse($this->mockUser()));

        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->getUserById('526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetUserByIdReturnsJsonResponseNotFound(): void
    {
        $this->queryBus->expects(self::once())
            ->method('ask')
            ->willReturn(new UserGetByIdResponse(null));

        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->getUserById('526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testGetUserByIdReturnsJsonResponseBadRequest(): void
    {
        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->getUserById('526586eb-d992-44e9-884b-4542bd3ec82b22');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testCreateUserReturnsJsonResponseCreated(): void
    {
        $request = $this->createMock(Request::class);
        $request->expects(self::once())
            ->method('getContent')
            ->willReturn($this->mockJsonRequest());

        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->createUser($request);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testCreateUserReturnsJsonResponseBadRequest(): void
    {
        $request = $this->createMock(Request::class);
        $request->expects(self::once())
            ->method('getContent')
            ->willReturn('');

        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->createUser($request);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUpdateUserReturnsJsonResponseOk(): void
    {
        $request = $this->createMock(Request::class);
        $request->expects(self::once())
            ->method('getContent')
            ->willReturn($this->mockJsonRequest());

        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->updateUser($request, '526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateUserReturnsJsonResponseBadRequest(): void
    {
        $request = $this->createMock(Request::class);
        $request->expects(self::once())
            ->method('getContent')
            ->willReturn('');

        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->updateUser($request, '526586eb-d992-44e9-884b-4542bd3ec82b');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUpdateUserReturnsJsonResponseUuidBadRequest(): void
    {
        $request = $this->createMock(Request::class);
        $request->expects(self::once())
            ->method('getContent')
            ->willReturn($this->mockJsonRequest());

        $this->commandBus->expects(self::once())
            ->method('dispatch')
            ->willThrowException(new InvalidUuidStringException());

        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->updateUser($request, '526586eb-d992-44e9-884b-4542bd3ec82b333');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUpdateUserReturnsJsonResponseNotFound(): void
    {
        $request = $this->createMock(Request::class);
        $request->expects(self::once())
            ->method('getContent')
            ->willReturn($this->mockJsonRequest());

        $this->commandBus->expects(self::once())
            ->method('dispatch')
            ->willThrowException(new \RuntimeException());

        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->updateUser($request, '526586eb-d992-44e9-884b-4542bd3ec82b333');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testDeleteUserReturnsJsonResponseNotContent(): void
    {
        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->deleteUser('526586eb-d992-44e9-884b-4542bd3ec82b333');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteUserReturnsJsonResponseUuidBadRequest(): void
    {
        $this->commandBus->expects(self::once())
            ->method('dispatch')
            ->willThrowException(new InvalidUuidStringException());

        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->deleteUser('526586eb-d992-44e9-884b-4542bd3ec82b333');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testDeleteUserReturnsJsonResponseNotFound(): void
    {
        $this->commandBus->expects(self::once())
            ->method('dispatch')
            ->willThrowException(new \RuntimeException());

        $controller = new UserApiController($this->queryBus, $this->commandBus, $this->userSerializer);
        $response = $controller->deleteUser('526586eb-d992-44e9-884b-4542bd3ec82b333');

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function mockUser(): User
    {
        return new User(
            'nombre',
            'email',
        );
    }

    /**
     * @throws \JsonException
     */
    public function mockJsonRequest(): string
    {
        return '{
            "name": "string",
            "email": "string",
            "password": "string"
        }';
    }
}
