<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Documents\Comments;
use App\Documents\Tags;
use App\TestService;
use Dom\Comment;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Documents\BlogPost;

class TrackController
{

    public function __construct(
        public TestService     $testService,
        public DocumentManager $dm
    )
    {
    }

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


        $response->getBody()->write(json_encode($data->getTags()->toArray()));
        return $response;
    }

    public function save(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();

        $comment = new Comments();
        $comment->text='COMMENT';

        $tag = new Tags();
        $tag->tag='TAG';

        $post = new BlogPost();
        $post->addComment($comment);
        $post->addTag($tag);

        $this->dm->persist($comment);
        $this->dm->persist($post);
        $this->dm->flush();

        $response->getBody()->write(json_encode($post));
        return $response;
    }
}
