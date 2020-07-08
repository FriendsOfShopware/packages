<?php declare(strict_types=1);

namespace App\Components;

use App\Components\Api\Exceptions\AccessDeniedException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ErrorSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onError',
        ];
    }

    public function onError(ExceptionEvent $event): void
    {
        if ($_SERVER['APP_ENV'] !== 'prod') {
            return;
        }

        if ($event->getThrowable() instanceof AccessDeniedException || $event->getThrowable() instanceof AccessDeniedHttpException) {
            $event->setResponse(new RedirectResponse('/account'));
        }

        if ($event->getThrowable() instanceof HttpException) {
            $event->setResponse(
                new JsonResponse(
                    [
                       'message' => $event->getThrowable()->getMessage(),
                    ],
                    $event->getThrowable()->getStatusCode()
                )
            );
        }
    }
}
