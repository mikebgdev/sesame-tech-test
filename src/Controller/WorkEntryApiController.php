<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Sesame\Application\Command\WorkEntryDeleteCommand;
use App\Sesame\Application\Command\WorkEntryEndCommand;
use App\Sesame\Application\Command\WorkEntryStartCommand;
use App\Sesame\Application\Query\Response\WorkEntryGetAllByUseResponse;
use App\Sesame\Application\Query\Response\WorkEntryGetByIdResponse;
use App\Sesame\Application\Query\WorkEntryGetAllByUserQuery;
use App\Sesame\Application\Query\WorkEntryGetByIdQuery;
use App\Sesame\Application\Service\WorkEntrySerializer;
use App\Sesame\Domain\Entity\User;
use App\Sesame\Domain\Entity\WorkEntry;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;

class WorkEntryApiController extends AbstractController
{
    private QueryBus $queryBus;
    private CommandBus $commandBus;
    private WorkEntrySerializer $workEntrySerializer;

    /**
     * @param QueryBus            $queryBus
     * @param CommandBus          $commandBus
     * @param WorkEntrySerializer $workEntrySerializer
     */
    public function __construct(QueryBus $queryBus, CommandBus $commandBus, WorkEntrySerializer $workEntrySerializer)
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
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

        /** @var WorkEntryGetAllByUseResponse $getAllWorkEntriesByUseResponse */
        $getAllWorkEntriesByUseResponse = $this->queryBus->ask(
            new WorkEntryGetAllByUserQuery($user->getId())
        );

        $workEntries = $getAllWorkEntriesByUseResponse->getWorkEntries();

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
                description: 'Invalid WorkEntry UUID'
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
            $uuid = Uuid::fromString($id);
            /** @var WorkEntryGetByIdResponse $getWorkEntryByIdResponse */
            $getWorkEntryByIdResponse = $this->queryBus->ask(
                new WorkEntryGetByIdQuery($uuid)
            );

            $workEntry = $getWorkEntryByIdResponse->getWorkEntry();
        } catch (InvalidUuidStringException $e) {
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
                response: Response::HTTP_CREATED,
                description: 'WorkEntry created successfully',
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'User not authenticated'
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

        $this->commandBus->dispatch(
            new WorkEntryStartCommand(
                $user->getId()
            )
        );

        return new JsonResponse('WorkEntry created successfully', Response::HTTP_CREATED);
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
                description: 'WorkEntry ended successfully'
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid WorkEntry UUID'
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
    public function endWorkEntry(Request $request, string $id): JsonResponse
    {
        /** @var ?User $user */
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $this->commandBus->dispatch(
                new WorkEntryEndCommand(
                    $id
                )
            );
        } catch (\RuntimeException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (InvalidUuidStringException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('WorkEntry ended successfully', Response::HTTP_OK);
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
                response: Response::HTTP_NO_CONTENT,
                description: 'WorkEntry deleted successfully'
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid UUID'
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
    public function deleteWorkEntry(string $id): JsonResponse
    {
        /** @var ?User $user */
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $this->commandBus->dispatch(
                new WorkEntryDeleteCommand(
                    $id
                )
            );
        } catch (\RuntimeException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (InvalidUuidStringException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('WorkEntry deleted successfully', Response::HTTP_NO_CONTENT);
    }
}
