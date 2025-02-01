<?php

declare(strict_types=1);

use App\Controllers\TrackController;
use App\Middleware\JsonResponseHeader;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

error_reporting(E_ALL & ~E_DEPRECATED);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

// Create and configure container
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$container = $containerBuilder->build();    

// Create and configure app
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add Middlewares
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true)
    ->getDefaultErrorHandler()
    ->forceContentType('application/json');
$app->add(new JsonResponseHeader());

$app->group('/track', function ($app) {
    $app->get('/all', TrackController::class . ':all');
    $app->get('/one/{id:[0-9a-z]+}', TrackController::class . ':one');
    $app->post('/save', TrackController::class . ':save');
});

$app->run();
