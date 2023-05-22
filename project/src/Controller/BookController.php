<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    #[Route('/livres', name: 'app_book')]
    public function index(EntityManagerInterface $manager, BookRepository $bookRepository): Response
    {

        /* This code is creating a query to retrieve data from the database using Doctrine's
        EntityManager. The query selects specific fields from the Image, Book, and Author entities
        and joins them together based on their relationships. It also filters the results to only
        include records where the book ID matches the image's book ID. Finally, it groups the
        results by specific fields and limits the number of results to 1. The result of the query is
        stored in the `books` variable. */
        $books = $manager->createQuery("SELECT i.id, i.url, i.name, b.slug, b.id, b.title, b.introduction, b.description, u.firstName, u.lastName
                                            FROM App\Entity\Image i 
                                            JOIN i.book b
                                            JOIN b.authors u
                                            WHERE b.id =  i.book
                                            GROUP BY i.id, b.slug, b.id, u.firstName, u.lastName
                                            ")->setMaxResults(1)->getResult();
        
        return $this->render('book/index.html.twig', [
            'books' => $books,
            'booksRepo' => $bookRepository->findAll()
        ]);
    }

    #[Route('/livre/{slug}', name: 'app_book_show')]
    public function show(BookRepository $bookRepository, ImageRepository $imageRepository, string $slug): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $bookRepository->findOneBySlug($slug),
            'images' => $imageRepository->findOneByUrl($slug)
        ]);
    }
}
