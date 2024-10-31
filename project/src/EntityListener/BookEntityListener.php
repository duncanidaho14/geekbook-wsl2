<?php

namespace App\EntityListener;

use App\Event\BookPublishedEvent;
use DateTimeInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookEntityListener implements EventSubscriberInterface
{
    public function __construct(private readonly SerializerInterface $serializerInterface, private readonly HubInterface $hubInterface){}

    public static function getSubscribedEvents()
    {
        return [
            BookPublishedEvent::class => ['onPublishedMessage']
        ];
    }

    public function onPlublishedMessage(BookPublishedEvent $messageEvent)
    {
        $message = $messageEvent->getBook();
        $channel = $message->getId();
        $update = new Update("/livre/$channel", $this->serializerInterface->serialize([
            'type' => 'livres',
            'data' => [
                'id' => $message->getId(),
                'title' => $message->getTitle(),
                'content' => $message->getDescription(),
                'author' => $message->getAuthors(),
                'rating' => $message->getRating(),
                'price' => $message->getPrice(),
                'publishedAt' => $message->getPublishedAt()->format(DateTimeInterface::ATOM)
            ],
        ], 'json', []), false);
        $this->hubInterface->publish($update);
    }
}
