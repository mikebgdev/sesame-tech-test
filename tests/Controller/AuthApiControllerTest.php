<?php
/*
 * This class is part of a software application developed by Michael Ballester Granero.
 */

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\AuthApiController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthApiControllerTest extends TestCase
{
    public function testLoginReturnsJsonResponse(): void
    {
        $request = $this->createMock(Request::class);

        $controller = new AuthApiController();
        $response = $controller->login($request);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
