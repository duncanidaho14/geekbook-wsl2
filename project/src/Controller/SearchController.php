<?php

namespace App\Controller;

use App\Entity\Book;
use Meilisearch\Client;
use Meilisearch\Bundle\SearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(SearchService $searchService, Request $request, EntityManagerInterface $manager): Response
    {        
        $client = new Client("http://meilisearch:7700", '!ChangeMe!');
        $books = $client->createIndex('app_dev_books', ['primaryKey' => 'id']);
        $client->index('app_dev_books')->addDocuments($books);
        $client->getTask(0);
        
        $searchQuery = $request->query->get('q') ?? '';
        $hits = $searchService->search($manager, Book::class, $searchQuery); 

        return $this->render('search/index.html.twig', [
            'books' => $hits,
            'q' => $searchQuery
        ]);
    }
}
