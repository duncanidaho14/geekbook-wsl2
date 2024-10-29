<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class PublishController extends AbstractController
{
    public function publish(HubInterface $default, Book $book): Response
    {
        header('Access-Control-Allow-Origin', 'https://mercure.docker.localhost:3200');
        header('Access-Control-Allow-Methods', 'GET', 'POST', 'PUT', 'DELETE', 'OPTIONS');
        header('Access-Control-Allow-Headers', 'Content-Type');
        header('CORS_ALLOWED_ORIGINS', 'https://mercure.docker.localhost:3200');
        $slug = $book->getSlug();

        $update = new Update(
            'https://gkbook.traefik.me/livre/'+ $slug,
            json_encode(['title' => $book->getTitle() ])
        );

        $default->publish($update);

        return new Response('published!');
    }
}