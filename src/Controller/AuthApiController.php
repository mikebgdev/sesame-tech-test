<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Controller;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthApiController extends AbstractController
{
    #[OA\Post(
        path: '/api/login',
        summary: 'Login and get a JWT token',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'password', type: 'string'),
                ],
                type: 'object'
            )
        ),
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Successful login',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Invalid credentials'
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'User not found'
            ),
        ]
    )]
    #[OA\Tag(name: 'Auth')]
    public function login(Request $request): JsonResponse
    {
        return new JsonResponse(['token' => ''], Response::HTTP_OK);
    }
}
