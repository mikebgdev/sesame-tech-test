<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

namespace App\Controller;

use App\Application\Service\UserSerializer;
use App\Application\Service\UserService;
use App\Domain\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserApiController extends AbstractController
{
    private UserService $userService;
    private UserSerializer $userSerializer;

    public function __construct(UserService $userService, UserSerializer $userSerializer)
    {
        $this->userService = $userService;
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
        $users = $this->userService->getAllUsers();

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
            $user = $this->userService->getUserById($id);
        } catch (BadRequestHttpException $e) {
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
                response: Response::HTTP_OK,
                description: 'User created successfully',
                content: new OA\JsonContent(ref: new Model(type: User::class))
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
            $user = $this->userService->createUser($request);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $userResponse = $this->userSerializer->serialize($user);

        return new JsonResponse($userResponse, Response::HTTP_OK);
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
                description: 'User updated successfully',
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
    public function updateUser(Request $request, string $id): JsonResponse
    {
        try {
            $user = $this->userService->updateUser($request, $id);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        if (null === $user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $userResponse = $this->userSerializer->serialize($user);

        return new JsonResponse($userResponse, Response::HTTP_OK);
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
                response: Response::HTTP_OK,
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
            $user = $this->userService->deleteUser($id);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        if (null === $user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $userResponse = $this->userSerializer->serialize($user);

        return new JsonResponse($userResponse, Response::HTTP_OK);
    }
}
