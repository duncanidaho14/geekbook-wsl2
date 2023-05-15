<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(EntityManagerInterface $manager, BookRepository $bookRepository): Response
    {
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

    #[Route('/book/{slug}', name: 'app_book_show')]
    public function show(BookRepository $bookRepository, string $slug): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $bookRepository->findOneBySlug($slug)
        ]);
    }
}
