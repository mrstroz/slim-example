<?php

declare(strict_types=1);

namespace App\Controllers;

use App\TestService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Valitron\Validator;

class TrackController
{

    public function __construct(
        public TestService $testService,
        public Validator $validator
    ) {

        $this->validator->mapFieldsRules([
            'name' => ['required','slug'],
        ]);
    }

    public function visit(Request $request, Response $response, array $args): Response
    {
        $body = json_encode(['test' => $this->testService->run()]);
        $response->getBody()->write($body);
        return $response;
    }

    public function one(Request $request, Response $response, array $args): Response
    {
        $body = json_encode(['id' => $args['id']]);
        $response->getBody()->write($body);
        return $response;
    }

    public function save(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();

        if (!$this->validator->withData($body)->validate()) {
            $response->getBody()->write(json_encode($this->validator->errors()));
            return $response->withStatus(422);
        }

        $html = var_export($body, true);
        $response->getBody()->write($_ENV['MONGO_URI']);
        return $response;
    }
}
