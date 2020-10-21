<?php

declare(strict_types=1);

use MeiliSearch\Client;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\RedisSessionHandler;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\', __DIR__ . '/../src/')
        ->exclude([__DIR__ . '/../src/DependencyInjection/', __DIR__ . '/../src/Entity/', __DIR__ . '/../src/Kernel.php', __DIR__ . '/../src/Tests/']);

    $services->load('App\Controller\\', __DIR__ . '/../src/Controller/')
        ->tag('controller.service_arguments');

    $services->alias(CacheInterface::class, Psr16Cache::class);

    $services->set(Psr16Cache::class);

    $services->set(Client::class)
        ->autowire(false)
        ->args(['%env(MELLISEARCH_URL)%']);

    $services->set('Redis', 'Redis')
        ->factory([RedisAdapter::class, 'createConnection'])
        ->args(['%env(REDIS_URL)%']);

    $services->set(RedisSessionHandler::class)
        ->args([ref('Redis')]);
};
