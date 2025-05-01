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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\UX\Turbo\TurboBundle;


class SearchController extends AbstractController
{
    public function __construct(
        private readonly SearchService $searchService,
        private SerializerInterface $serializer
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
            
            $searchResponse = $searchService->rawSearch(Book::class, $searchQuery, [
                'attributesToHighlight' => ['title', 'introduction'],
                'highlightPreTag' => '<mark>',
                'highlightPostTag' => '</mark>',
                'attributesToCrop' => ['introduction'],
                'cropLength' => 20,
            ]);
            $results = $searchResponse['hits'];
            
            // Check if the request is a Turbo Stream request
            // If the request is a Turbo Stream request, we will return a Turbo Stream response
            // If the request is an AJAX request, we will return a JSON response
            // If the request is a normal request, we will return a normal HTML response
            // 🔥 The magic happens here! 🔥
            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                
                // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
               
            } elseif ($request->isXmlHttpRequest()) {
                // If the request is an AJAX request, set the content type as application/json and return JSON
                $request->setRequestFormat('application/json');
                $request->setResponseFormat('application/json');
            } else {
                // Otherwise, render the full page
                $request->setRequestFormat('html');
                $this->redirectToRoute('app_search');
            }

        }

        $hits = $searchService->search($manager, Book::class, $searchQuery);
        

        // $template = $request->isXmlHttpRequest() ? 'search/index.html.twig' : 'search/index.html.twig';

        return $this->render('search/index.html.twig', [
                'books' => $this->serializer->serialize($hits, 'json', ['groups' => ['searchable']]),
                'searchForm' => $searchForm->createView(),
                'searchQuery' => $searchQuery,
                'count' => count($hits),
                'booksAll' => $manager->getRepository(Book::class)->findAll(),
                'bookslessexpensive' => $manager->getRepository(Book::class)->findBy([], ['price' => 'ASC'], 12),
                'booksmorestars' => $manager->getRepository(Book::class)->findBy([], ['rating' => 5, 'rating' => 'DESC'], 12),
                'categories' => $manager->getRepository('App\Entity\Category')->findAll(),
                'results' => $results ?? [],
            ]);
    }
}
