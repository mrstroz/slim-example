<?php

declare(strict_types=1);

use DI\Container;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AttributeDriver;
use function DI\factory;

// Must return an array of definitions
return [
    Configuration::class => factory(function () {
        $config = new Configuration();
        $config->setProxyDir(__DIR__ . '/../src/Proxies');
        $config->setProxyNamespace('Proxies');
        $config->setHydratorDir(__DIR__ . '/../src/Hydrators');
        $config->setHydratorNamespace('Hydrators');
        $config->setDefaultDB('doctrine_odm');
        $config->setMetadataDriverImpl(AttributeDriver::create(__DIR__ . '/../src/App/Documents'));

        spl_autoload_register($config->getProxyManagerConfiguration()->getProxyAutoloader());

        return $config;
    }),

    DocumentManager::class => factory(function (Container $container) {
        $config = $container->get(Configuration::class);
        $client = new \MongoDB\Client($_ENV['MONGO_URI'] ?? 'mongodb://localhost:27017');
        
        return DocumentManager::create($client, $config);
    })
];