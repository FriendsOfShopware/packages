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
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

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

        if ($event->getThrowable() instanceof AccessDeniedException || $event->getThrowable() instanceof AccessDeniedHttpException || $event->getThrowable() instanceof InsufficientAuthenticationException) {
            $event->setResponse(new RedirectResponse('/account'));

            return;
        }

        if ($event->getThrowable() instanceof HttpException) {
            $userAgent = $event->getRequest()->headers->get('User-Agent', '');
            $key = str_contains($userAgent, 'Composer') ? 'warning' : 'message';

            $event->setResponse(
                new JsonResponse(
                    [
                        $key => $event->getThrowable()->getMessage(),
                    ],
                    $event->getThrowable()->getStatusCode()
                )
            );
        }
    }
}
