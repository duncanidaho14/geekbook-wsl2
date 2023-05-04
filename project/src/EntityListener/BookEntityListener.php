<?php

namespace App\EntityListener;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: Book::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Book::class)]
class BookEntityListener
{
    private $slugger;
    
    public function __construct(SluggerInterface $slugger) 
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Book $book, LifecycleEventArgs $event)
    {
        $book->computeSlug($this->slugger);
    }

    public function preUpdate(Book $book, LifecycleEventArgs $event)
    {
        $book->computeSlug($this->slugger);
    }
}