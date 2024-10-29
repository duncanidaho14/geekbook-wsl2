<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Discovery;

class DiscoverController extends AbstractController
{
    public function discover(Request $request, Discovery $discovery, Book $book): JsonResponse
    {
        // Link: <https://hub.example.com/.well-known/mercure>; rel="mercure"
        $discovery->addLink($request);
        $slug = $book->getSlug();
        return $this->json([
            '@id' => '/livre\/'+ $slug,
            'availability' => 'https://schema.org/InStock',
        ]);
    }
}