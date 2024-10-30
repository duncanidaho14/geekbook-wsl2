<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class PublishController extends AbstractController
{
    public function publish(HubInterface $default, Book $book, Request $request): Response
    {
        
        
        $slug = $book->getSlug();

        $update = new Update(
            'https://gkbook.traefik.me/livre/'+ $slug,
            json_encode(['title' => $book->getTitle() ])
        );

        $default->publish($update);

        return new Response('published!');
    }
}