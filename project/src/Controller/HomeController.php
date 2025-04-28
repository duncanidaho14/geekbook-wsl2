<?php

namespace App\Controller;

use Predis\Client;
use App\Entity\Book;
use App\Form\SearchFormType;
use App\Repository\BookRepository;
use App\Repository\ImageRepository;
use Meilisearch\Bundle\SearchService;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\Monolog\HandlerConfig\PredisConfig;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    

    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $manager, BookRepository $bookRepository, ImageRepository $imageRepository, CategoryRepository $categoriesRepository): Response
    {
        $lastBooks = $manager->createQuery("SELECT i.id, i.url, i.name, b.slug, b.id, b.title, b.introduction, b.description, b.price, b.rating
                                            FROM App\Entity\Image i
                                            JOIN  App\Entity\Book b WITH i.id = b.id
                                        ")->setMaxResults(12)->getResult();


        
        $lastAuthors = $manager->createQuery('SELECT a.id, a.firstName, a.lastName, a.description, b.id, b.title, b.introduction, b.publishedAt, i.name, i.url, c.name as catName, c.image
                                                FROM App\Entity\Author a
                                                JOIN a.book b
                                                JOIN b.categories c
                                                JOIN b.images i
                                                WHERE a.firstName = a.firstName AND a.lastName = a.lastName
                                                GROUP BY a.id, a.firstName, a.lastName, a.description, b.id, b.title, b.introduction, b.publishedAt, i.name, i.url, c.name, c.image
                                                ORDER BY b.publishedAt DESC
                                        ')->setMaxResults(4)->getResult();


        return $this->render('home/index.html.twig', [
            'books' => $lastBooks,
            'authors' => $lastAuthors,
            'imageRepo' => $imageRepository->findByUrl([]),
            'categories' => $categoriesRepository->findAll(),
            'find' => $bookRepository->findByBookDate($request->query->get('publishedAt')),
            'booksAll' => $bookRepository->findAll(),
            'bookslessexpensive' => $bookRepository->findBy([], ['price' => 'ASC'], 12),
            'booksmorestars' => $bookRepository->findBy([], ['rating' => 5, 'rating' => 'DESC'], 12),
  
        ]);
    }
}
