<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

namespace App\Controller;

use App\Sesame\Application\Command\CreateUserCommand;
use App\Sesame\Application\Command\DeleteUserCommand;
use App\Sesame\Application\Command\UpdateUserCommand;
use App\Sesame\Application\Query\GetAllUsersQuery;
use App\Sesame\Application\Query\GetUserByIdQuery;
use App\Sesame\Application\Query\Response\GetAllUsersResponse;
use App\Sesame\Application\Query\Response\GetUserByIdResponse;
use App\Sesame\Application\Service\UserSerializer;
use App\Sesame\Domain\Entity\User;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserApiController extends AbstractController
{
    private QueryBus $queryBus;
    private CommandBus $commandBus;
    private UserSerializer $userSerializer;

    public function __construct(QueryBus $queryBus, CommandBus $commandBus, UserSerializer $userSerializer)
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->userSerializer = $userSerializer;
    }

    #[OA\Get(
        path: '/api/users',
        summary: 'Get all users',
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Users found',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: User::class))
                )
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Users not found'
            ),
        ]
    )]
    #[OA\Tag(name: 'Users')]
    public function getAllUsers(): JsonResponse
    {
        /** @var GetAllUsersResponse $getAllUsersResponse
         */
        $getAllUsersResponse = $this->queryBus->ask(
            new GetAllUsersQuery()
        );

        $users = $getAllUsersResponse->getUsers();

        if ([] === $users) {
            return new JsonResponse(['message' => 'Users not found'], Response::HTTP_NOT_FOUND);
        }

        $usersResponse = $this->userSerializer->serializeCollection($users);

        return new JsonResponse($usersResponse, Response::HTTP_OK);
    }

    #[OA\Get(
        path: '/api/users/{id}',
        summary: 'Get user by ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of user to return',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'User found',
                content: new OA\JsonContent(ref: new Model(type: User::class))
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid UUID'
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'User not found'
            ),
        ]
    )]
    #[OA\Tag(name: 'Users')]
    public function getUserById(string $id): JsonResponse
    {
        try {
            $uuid = Uuid::fromString($id);
            /** @var GetUserByIdResponse $getUserByIdResponse
             */
            $getUserByIdResponse = $this->queryBus->ask(
                new GetUserByIdQuery($uuid)
            );
            $user = $getUserByIdResponse->getUser();
        } catch (InvalidUuidStringException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        if (null === $user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $userResponse = $this->userSerializer->serialize($user);

        return new JsonResponse($userResponse, Response::HTTP_OK);
    }

    #[OA\Post(
        path: '/api/users',
        summary: 'Create a new user',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'password', type: 'string'),
                ],
                type: 'object'
            )
        ),
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'User created successfully'
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid JSON data'
            ),
        ]
    )]
    #[OA\Tag(name: 'Users')]
    public function createUser(Request $request): JsonResponse
    {
        try {
            $data = \json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);
            $this->commandBus->dispatch(
                new CreateUserCommand(
                    $data['name'],
                    $data['email'],
                    $data['password']
                )
            );
        } catch (\JsonException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('User created successfully', Response::HTTP_CREATED);
    }

    #[OA\Put(
        path: '/api/users/{id}',
        summary: 'Update user by ID',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'password', type: 'string'),
                ],
                type: 'object'
            )
        ),
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of user to return',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'User updated successfully'
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Syntax Error | Invalid UUID'
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'User not found'
            ),
        ]
    )]
    #[OA\Tag(name: 'Users')]
    public function updateUser(Request $request, string $id): JsonResponse
    {
        try {
            $data = \json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);
            $command = new UpdateUserCommand($id, $data['name'] ?? null, $data['email'] ?? null, $data['password'] ?? null);
            $this->commandBus->dispatch($command);
        } catch (\RuntimeException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\JsonException|InvalidUuidStringException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('User updated successfully', Response::HTTP_OK);
    }

    #[OA\Delete(
        path: '/api/users/{id}',
        summary: 'Delete user by ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of user to return',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_NO_CONTENT,
                description: 'User deleted successfully',
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid UUID'
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'User not found',
            ),
        ]
    )]
    #[OA\Tag(name: 'Users')]
    public function deleteUser(string $id): JsonResponse
    {
        try {
            $command = new DeleteUserCommand($id);
            $this->commandBus->dispatch($command);
        } catch (\RuntimeException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (InvalidUuidStringException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('User deleted successfully', Response::HTTP_NO_CONTENT);
    }
}
