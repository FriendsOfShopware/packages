<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('framework', ['cache' => ['app' => 'cache.adapter.redis', 'default_redis_provider' => '%env(REDIS_URL)%']]);
};
