<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Documents\BlogPost;
use App\Documents\Comments;
use App\Documents\Tags;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Serializer\Serializer;

class TestController
{

    public function __construct(
        public DocumentManager $dm,
        public Serializer      $serializer
    )
    {

    }

    public function read(Request $request, Response $response, array $args): Response
    {
        $data = $this->dm->getRepository(BlogPost::class)->findAll();

        $body = $this->serializer->serialize($data, 'json', ['groups' => ['default']]);
        $response->getBody()->write($body);
        return $response;
    }

    public function readOne(Request $request, Response $response, array $args): Response
    {
        $data = $this->dm->find(BlogPost::class, $args['id']);
        if (!$data) {
            $response->getBody()->write(json_encode(['error' => 'Not found']));
            return $response->withStatus(404);
        }

        $body = $this->serializer->serialize($data, 'json', ['groups' => ['default']]);
        $response->getBody()->write($body);
        return $response;
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();

        $comment = new Comments();
        $comment->text = 'COMMENT';

        $tag = new Tags();
        $tag->tag = 'TAG';

        $post = new BlogPost();
        $post->addComment($comment);
        $post->addTag($tag);

        $this->dm->persist($comment);
        $this->dm->persist($post);
        $this->dm->flush();

        $body = $this->serializer->serialize($post, 'json', ['groups' => ['default']]);
        $response->getBody()->write($body);
        return $response;
    }
}
