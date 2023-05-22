<?php

namespace App\Controller;

use App\Entity\Book;
use Meilisearch\Client;
use App\Form\SearchFormType;
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
        // $client = new Client("http://meilisearch:7700", '!ChangeMe!');
        // $books = $client->createIndex('app_dev_books', ['primaryKey' => 'id']);
        // $client->index('app_dev_books')->addDocuments($books);
        //$client->getTask(0);
        
        $searchForm = $this->createForm(SearchFormType::class, null, [
            'method' => 'GET',
            'csrf_protection' => false
        ]);

        $searchQuery = $request->query->get('q') ?? '';

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchResponse = $searchService->rawSearch(Book::class, $searchQuery, [

            ]);
            $results = $searchResponse['hits'];
        }

        $hits = $searchService->search($manager, Book::class, $searchQuery); 

        return $this->render('search/index.html.twig', [
            'books' => $hits,
            'searchQuery' => $searchQuery,
            'searchForm' => $searchForm,
            'results' => $results ?? []
        ]);
    }
}
