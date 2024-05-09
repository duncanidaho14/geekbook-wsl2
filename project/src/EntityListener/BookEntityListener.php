<?php

namespace App\EntityListener;

use App\Entity\Book;
use Doctrine\ORM\Event\PostPersistEventArgs;

class BookEntityListener
{
    public function prePersist(Book $book, PostPersistEventArgs $eventArgs): void
    {
        dd($eventArgs->getObject());
    }

    public function preUpdate(Book $book, PostPersistEventArgs $eventArgs): void
    {
        dd($eventArgs->getObject());
    }
    // private $slugger;

    // public function __construct(SluggerInterface $slugger)
    // {
    //     $this->slugger = $slugger;
    // }

    // public function prePersist(Book $book, LifecycleEventArgs $event)
    // {
    //     $book->computeSlug($this->slugger);
    //     $event->getObject();
    // }

    // public function preUpdate(Book $book, LifecycleEventArgs $event)
    // {
    //     $book->computeSlug($this->slugger);
    //     $event->getObject();
    // }
}
