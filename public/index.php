<?php

declare(strict_types=1);

use App\Controllers\TrackController;
use App\Middleware\JsonResponseHeader;
use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/..");
$dotenv->load();

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$app->addErrorMiddleware(true, true, true)
    ->getDefaultErrorHandler()
    ->forceContentType('application/json');

$app->add(new JsonResponseHeader());

$app->group('/track', function ($app) {
    $app->get('/visit', TrackController::class . ':visit');
    $app->get('/visit/{id:[0-9]+}', TrackController::class . ':one');
    $app->post('/save', TrackController::class . ':save');
});

$app->run();
