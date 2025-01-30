<?php

declare(strict_types=1);

namespace App\Controllers;

use App\TestService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TrackController
{

    public function __construct(public TestService $testService)
    {
    }

    public function visit(Request $request, Response $response, array $args): Response
    {
        $body = json_encode(['test' => $this->testService->run()]);
        $response->getBody()->write($body);
        return $response;
    }

    public function visitOne(Request $request, Response $response, array $args): Response
    {
        $body = json_encode(['id' => $args['id']]);
        $response->getBody()->write($body);
        return $response;
    }

}