<?php

declare(strict_types=1);

namespace App\Controllers;

use App\TestService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Documents\BlogPost;

class TrackController
{

    public function __construct(
        public TestService $testService,
        public DocumentManager $dm
    ) {}

    public function all(Request $request, Response $response, array $args): Response
    {
        $data = $this->dm->getRepository(BlogPost::class)->findAll();

        $body = json_encode($data);
        $response->getBody()->write($body);
        return $response;
    }

    public function one(Request $request, Response $response, array $args): Response
    {
        $body = json_encode(['id' => $args['id']]);

        $data = $this->dm->find(BlogPost::class, $args['id']);
        if (!$data) {
            $response->getBody()->write(json_encode(['error' => 'Not found']));
            return $response->withStatus(404);
        }

        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function save(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();

        $post = new BlogPost(
            title: $body['title'] ?? '',
            body: $body['body'] ?? '',
        );

        $this->dm->persist($post);
        $this->dm->flush();

        $response->getBody()->write(json_encode($post));
        return $response;
    }
}
