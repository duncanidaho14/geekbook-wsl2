<?php

namespace App\EntityListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CacheControlSubscriber implements EventSubscriberInterface
{
    public function onUserLogout(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onUserLogout',
        ];
    }
}
// Déprécier
// class CacheControlListener
// {
//     public const ROUTES_NOT_TO_CACHE = ['admin'];

//     public function onKernelResponse(FilterResponseEvent $event)
//     {
//         if(in_array($event->getRequest()->attributes->get('_route'), self::ROUTES_NOT_TO_CACHE)) {
//             $headers = $event->getResponse()->headers;

//             $headers->set(
//                 'Cache-Control',
//                 'no-cache, no-store, max-age=0, must-revalidate'
//             );
//         }
//     }
// }