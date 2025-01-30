<?php

declare(strict_types=1);

use App\Controllers\TrackController;
use App\Middleware\JsonResponseHeader;
use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true)
    ->getDefaultErrorHandler()
    ->forceContentType('application/json');

$app->add(new JsonResponseHeader());

$app->get('/track/visit', TrackController::class . ':visit');
$app->get('/track/visit/{id:[0-9]+}', TrackController::class . ':visitOne');

$app->run();