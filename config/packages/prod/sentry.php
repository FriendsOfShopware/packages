<?php

declare(strict_types=1);

use App\Exception\AccessDeniedToDownloadPluginHttpException;
use App\Exception\InvalidShopGivenHttpException;
use App\Exception\InvalidTokenHttpException;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('sentry', ['dsn' => '%env(SENTRY_DSN)%']);

    $containerConfigurator->extension('sentry', ['options' => ['environment' => '%kernel.environment%', 'release' => '%env(VERSION)%', 'excluded_exceptions' => [NotFoundHttpException::class, InsufficientAuthenticationException::class, AccessDeniedException::class, InvalidTokenHttpException::class, AccessDeniedToDownloadPluginHttpException::class, InvalidShopGivenHttpException::class]]]);
};
