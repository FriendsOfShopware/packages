<?php

declare(strict_types=1);

use App\Components\Api\AccessToken;
use App\Components\Security\AccessDeniedHandler;
use App\Components\Security\UserProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('security', ['providers' => ['api' => ['id' => UserProvider::class]]]);

    $containerConfigurator->extension('security', ['firewalls' => ['dev' => ['pattern' => '^/(_(profiler|wdt)|css|images|js)/', 'security' => false], 'main' => ['anonymous' => true, 'user_checker' => AccessToken::class, 'access_denied_handler' => AccessDeniedHandler::class, 'logout' => ['path' => 'app_logout']]]]);
};
