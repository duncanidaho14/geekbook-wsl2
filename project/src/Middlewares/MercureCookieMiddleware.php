<?php

namespace App\Middlewares;

use App\Entity\Book;
use App\Service\CookieGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class MercureCookieMiddleware implements EventSubscriberInterface
{
    public function __construct(private readonly Book $book, private readonly CookieGenerator $cookieGenerator){}

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse']
        ];
    }

    public function onKernelResponse(ResponseEvent $kernelEvents)
    {
        $response = $kernelEvents->getResponse();
        $request = $kernelEvents->getRequest();

        if(HttpKernelInterface::MASTER_REQUEST !== $kernelEvents->getRequestType()
            || !in_array('text/html', $request->getAcceptableContentTypes())
            || (!$book = $this->book->getAuthors() instanceof Book)
        )
        {
                return;
        }
        $response->headers->setcookie($this->cookieGenerator->generate($book));
    }
}