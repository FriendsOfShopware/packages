<?php

declare(strict_types=1);

namespace App\Components;

use App\Components\Api\Exceptions\AccessDeniedException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Twig\Environment;

class ErrorSubscriber implements EventSubscriberInterface
{
    public function __construct(private Environment $twig)
    {
    }

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

        $userAgent = $event->getRequest()->headers->get('User-Agent', '');

        if ($event->getThrowable() instanceof HttpException && !\str_contains($userAgent, 'Mozilla')) {
            $key = \str_contains($userAgent, 'Composer') ? 'warning' : 'message';

            $event->setResponse(
                new JsonResponse(
                    [
                        $key => $event->getThrowable()->getMessage(),
                    ],
                    $event->getThrowable()->getStatusCode()
                )
            );

            return;
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        $tpl = $this->twig->render('error.html.twig', [
            'lastEventId' => $event->getRequest()->attributes->get('sentryId')
        ]);
        $response->setContent($tpl);

        $event->setResponse($response);
    }
}
