<?php

declare(strict_types=1);

use App\Controllers\TrackController;
use App\Middleware\JsonResponseHeader;
use DI\Container;
use Slim\Factory\AppFactory;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AttributeDriver;


require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$container = new Container();

$container->set(Configuration::class, function (Container $container) {
    $config = new Configuration();
    $config->setProxyDir(__DIR__ . '/src/Proxies');
    $config->setProxyNamespace('Proxies');
    $config->setHydratorDir(__DIR__ . '/src/Hydrators');
    $config->setHydratorNamespace('Hydrators');
    $config->setDefaultDB('doctrine_odm');
    $config->setMetadataDriverImpl(AttributeDriver::create(__DIR__ . '/src/Documents')); // Fixed typo

    return $config; // Remove the new Configuration() wrapper
});

$container->set(DocumentManager::class, function (Container $container) {
    $config = $container->get(Configuration::class);
    
    // Add MongoDB connection
    $client = new \MongoDB\Client($_ENV['MONGO_URI'] ?? 'mongodb://localhost:27017');
    
    return DocumentManager::create($client, $config);
});

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
