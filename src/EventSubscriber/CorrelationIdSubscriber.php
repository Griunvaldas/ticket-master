<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class CorrelationIdSubscriber implements EventSubscriberInterface
{
    private const CORRELATION_ID_HEADER = 'X-Correlation-ID';
    private const CORRELATION_ID_ATTRIBUTE = 'correlation_id';

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 255],
            KernelEvents::RESPONSE => ['onKernelResponse', 0],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        $correlationId = $request->headers->get(self::CORRELATION_ID_HEADER);

        if (null === $correlationId || '' === $correlationId) {
            $correlationId = bin2hex(random_bytes(16));
        }

        $request->attributes->set(self::CORRELATION_ID_ATTRIBUTE, $correlationId);
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $correlationId = $request->attributes->get(self::CORRELATION_ID_ATTRIBUTE);

        if (null === $correlationId) {
            return;
        }

        $event->getResponse()->headers->set(self::CORRELATION_ID_HEADER, $correlationId);
    }
}
