<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\SearchFormType;
use Meilisearch\Bundle\SearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    public function __construct(
        private readonly SearchService $searchService,
    ) {
    }

    #[Route('/rechercher', name: 'app_search')]
    public function index(SearchService $searchService, Request $request, EntityManagerInterface $manager): Response
    {
        $searchForm = $this->createForm(SearchFormType::class, null, [
            'method' => 'GET',
            'csrf_protection' => false
        ]);

        $searchQuery = $request->query->get('q') ?? '';

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchResponse = $searchService->search($manager, Book::class, $searchQuery, [
                'attributesToHighlight' => ['title', 'introduction'],
                'highlightPreTag' => '<mark>',
                'highlightPostTag' => '</mark>',
                'attributesToCrop' => ['introduction'],
                'cropLength' => 20,
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
