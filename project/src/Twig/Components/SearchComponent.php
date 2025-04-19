<?php

namespace App\Twig\Components;

use Symfony\Component\HttpFoundation\Request;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;



#[AsLiveComponent('searchComponent')]
class SearchComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?bool $isOpen = false;
    
    #[LiveProp(writable: true)]
    public ?string $title = '';

    #[LiveAction]
    public function hasOpen(): bool {
       return true;
    }

    #[liveAction]
    public function setTitle(Request $request): string {
        $this->title = $request->request->get('q');
        return $this->title;
    }

    // public function __invoke(SearchService $searchService, Request $request, EntityManagerInterface $manager): Response
    // {
    //     $searchForm = $this->createForm(SearchFormType::class, null, [
    //         'method' => 'GET',
    //         'csrf_protection' => false
    //     ]);

    //     $searchQuery = $request->query->get('q') ?? '';

    //     $searchForm->handleRequest($request);

    //     if ($searchForm->isSubmitted() && $searchForm->isValid()) {
    //         $searchResponse = $searchService->rawSearch(Book::class, $searchQuery, [
    //             'attributesToHighlight' => ['title', 'introduction'],
    //             'highlightPreTag' => '<mark>',
    //             'highlightPostTag' => '</mark>',
    //             'attributesToCrop' => ['introduction'],
    //             'cropLength' => 20,
    //         ]);
    //         $results = $searchResponse['hits'];
    //     }

    //     $hits = $searchService->search($manager, Book::class, $searchQuery);

    //     return $this->render('components/search.html.twig', [
    //         'books' => $hits,
    //         'searchQuery' => $searchQuery,
    //         'searchForm' => $searchForm,
    //         'results' => $results ?? []
    //     ]);
    // }
}
