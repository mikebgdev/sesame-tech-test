<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Application\Service\WorkEntrySerializer;
use App\Application\Service\WorkEntryService;
use App\Domain\Entity\User;
use App\Domain\Entity\WorkEntry;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class WorkEntryApiController extends AbstractController
{
    private WorkEntryService $workEntryService;
    private WorkEntrySerializer $workEntrySerializer;

    public function __construct(WorkEntryService $workEntryService, WorkEntrySerializer $workEntrySerializer)
    {
        $this->workEntryService = $workEntryService;
        $this->workEntrySerializer = $workEntrySerializer;
    }

    #[OA\Get(
        path: '/api/workentry',
        summary: 'Get all work entries by user',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'WorkEntries found',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: WorkEntry::class))
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid User UUID'
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'User not authenticated'
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'WorkEntries not found'
            ),
        ]
    )]
    #[OA\Tag(name: 'WorkEntry')]
    #[IsGranted('ROLE_USER')]
    public function getAllWorkEntriesByUser(Request $request): JsonResponse
    {
        /** @var ?User $user */
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $workEntries = $this->workEntryService->getAllWorkEntriesByUser($user->getId()->toString());
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        if ([] === $workEntries) {
            return new JsonResponse(['message' => 'WorkEntries not found'], Response::HTTP_NOT_FOUND);
        }

        $workEntriesResponse = $this->workEntrySerializer->serializeCollection($workEntries);

        return new JsonResponse($workEntriesResponse, Response::HTTP_OK);
    }

    #[OA\Get(
        path: '/api/workentry/{id}',
        summary: 'Get WorkEntry by ID',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'WorkEntry found',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: WorkEntry::class))
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid User UUID'
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'User not authenticated'
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'WorkEntry not found'
            ),
        ]
    )]
    #[OA\Tag(name: 'WorkEntry')]
    #[IsGranted('ROLE_USER')]
    public function getWorkEntryById(Request $request, string $id): JsonResponse
    {
        /** @var ?User $user */
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $workEntry = $this->workEntryService->getWorkEntryById($id);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        if (null === $workEntry) {
            return new JsonResponse(['message' => 'WorkEntry not found'], Response::HTTP_NOT_FOUND);
        }

        $workEntryResponse = $this->workEntrySerializer->serialize($workEntry);

        return new JsonResponse($workEntryResponse, Response::HTTP_OK);
    }

    #[OA\Post(
        path: '/api/workentry/start',
        summary: 'Create a new WorkEntry by User',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'WorkEntry created successfully',
                content: new OA\JsonContent(ref: new Model(type: WorkEntry::class))
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid JSON data'
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'WorkEntry not started'
            ),
        ]
    )]
    #[OA\Tag(name: 'WorkEntry')]
    #[IsGranted('ROLE_USER')]
    public function startWorkEntry(Request $request): JsonResponse
    {
        /** @var ?User $user */
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $workEntry = $this->workEntryService->startWorkEntry($user->getId()->toString());
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        if (null === $workEntry) {
            return new JsonResponse(['message' => 'WorkEntry not started'], Response::HTTP_NOT_FOUND);
        }

        $workEntryResponse = $this->workEntrySerializer->serialize($workEntry);

        return new JsonResponse($workEntryResponse, Response::HTTP_OK);
    }

    #[OA\Put(
        path: '/api/workentry/end/{id}',
        summary: 'End WorkEntry by ID',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of WorkEntry to return',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'WorkEntry ended successfully',
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid UUID'
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'WorkEntry not found',
            ),
        ]
    )]
    #[OA\Tag(name: 'WorkEntry')]
    #[IsGranted('ROLE_USER')]
    public function endWorkEntry(Request $request, string $id): JsonResponse
    {
        try {
            $workEntry = $this->workEntryService->endWorkEntry($id);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        if (null === $workEntry) {
            return new JsonResponse(['message' => 'WorkEntry not found'], Response::HTTP_NOT_FOUND);
        }

        $workEntryResponse = $this->workEntrySerializer->serialize($workEntry);

        return new JsonResponse($workEntryResponse, Response::HTTP_OK);
    }

    #[OA\Delete(
        path: '/api/workentry/{id}',
        summary: 'Delete WorkEntry by ID',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of WorkEntry to return',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'WorkEntry deleted successfully',
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid UUID'
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'WorkEntry not found',
            ),
        ]
    )]
    #[OA\Tag(name: 'WorkEntry')]
    #[IsGranted('ROLE_USER')]
    public function deleteWorkEntry(string $id): JsonResponse
    {
        try {
            $workEntry = $this->workEntryService->deleteWorkEntry($id);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        if (null === $workEntry) {
            return new JsonResponse(['message' => 'WorkEntry not found'], Response::HTTP_NOT_FOUND);
        }

        $workEntryResponse = $this->workEntrySerializer->serialize($workEntry);

        return new JsonResponse($workEntryResponse, Response::HTTP_OK);
    }
}
