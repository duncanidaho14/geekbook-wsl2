<?php

namespace App\Event;

use App\Entity\Book;

class BookPublishedEvent
{
    public function __construct(private readonly Book $book){}

    public function getBook(): Book
    {
        return $this->book;
    }
}
