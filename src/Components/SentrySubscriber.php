<?php

namespace App\Components;

use App\Components\Api\Exceptions\AccessDeniedException;
use App\Exception\AccessDeniedToDownloadPluginHttpException;
use App\Exception\InvalidShopGivenHttpException;
use App\Exception\InvalidTokenHttpException;
use Sentry\State\Scope;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

/**
 * Why not using the Sentry Bundle?
 *
 * The maintainers are SOOO slow. To make it easier and not depend on them, lets implement only what we need
 */
class SentrySubscriber implements EventSubscriberInterface
{
    /**
     * @var string[]
     */
    private array $ignoreExceptions = [
        NotFoundHttpException::class,
        InsufficientAuthenticationException::class,
        AccessDeniedException::class,
        InvalidTokenHttpException::class,
        AccessDeniedToDownloadPluginHttpException::class,
        InvalidShopGivenHttpException::class
    ];

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onController',
            KernelEvents::EXCEPTION => ['onError', 200],
        ];
    }

    public function onController(ControllerEvent $event): void
    {
        if ($_SERVER['APP_ENV'] !== 'prod') {
            return;
        }

        if (!$event->getRequest()->attributes->has('route')) {
            return;
        }

        $route = (string) $event->getRequest()->attributes->get('route');

        \Sentry\configureScope(static function (Scope $scope) use ($route) {
            $scope->setTag('route', $route);
        });
    }

    public function onError(ExceptionEvent $event): void
    {
        if ($_SERVER['APP_ENV'] !== 'prod') {
            return;
        }

        $eventClass = \get_class($event->getThrowable());

        if (\in_array($eventClass, $this->ignoreExceptions, true)) {
            return;
        }

        if (!isset($_SERVER['SENTRY_DSN'])) {
            return;
        }

        $sentryId = \Sentry\captureException($event->getThrowable());

        if ($sentryId) {
            $event->getRequest()->attributes->set('sentryId', (string) $sentryId);
        }
    }
}
