<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

namespace App\Controller;

use App\Application\Service\UserService;
use App\Domain\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    // TODO SERIALICER JSONRESPONSE
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[OA\Get(
        path: '/api/users',
        summary: 'Get all users',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Users found',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: User::class))
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Users not found'
            ),
        ]
    )]
    public function getAllUsers(): JsonResponse
    {
        $users = $this->userService->getAllUsers();

        return new JsonResponse($users, Response::HTTP_OK);
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
                response: 200,
                description: 'User found',
                content: new OA\JsonContent(ref: new Model(type: User::class))
            ),
            new OA\Response(
                response: 404,
                description: 'User not found'
            ),
        ]
    )]
    public function getUserById(string $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($user, Response::HTTP_OK);
    }

    /**
     * @throws \JsonException
     */
    #[OA\Post(
        path: '/api/users',
        summary: 'Create a new user',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'username', type: 'string'),
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'password', type: 'string'),
                ],
                type: 'object'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'User created successfully',
                content: new OA\JsonContent(ref: new Model(type: User::class))
            ),
            new OA\Response(
                response: 404,
                description: 'User not found'
            ),
        ]
    )]
    public function createUser(Request $request): JsonResponse
    {
        $user = $this->userService->createUser($request);

        // TODO EXCEPTION RESPONSE
        return new JsonResponse($user, Response::HTTP_OK);
    }

    /**
     * @throws \JsonException
     */
    #[OA\Put(
        path: '/api/users/{id}',
        summary: 'Update user by ID',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'username', type: 'string'),
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
                response: 200,
                description: 'User updated successfully',
                content: new OA\JsonContent(ref: new Model(type: User::class))
            ),
            new OA\Response(
                response: 404,
                description: 'User not found'
            ),
        ]
    )]
    public function updateUser(Request $request, string $id): JsonResponse
    {
        $user = $this->userService->updateUser($request, $id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($user, Response::HTTP_OK, [], true);
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
                response: 200,
                description: 'User deleted successfully',
            ),
            new OA\Response(
                response: 404,
                description: 'User not found',
            ),
        ]
    )]
    public function deleteUser(string $id): JsonResponse
    {
        $user = $this->userService->deleteUser($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($user, Response::HTTP_NO_CONTENT);
    }
}
