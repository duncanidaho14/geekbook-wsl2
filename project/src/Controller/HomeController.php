<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\ImageRepository;
use Meilisearch\Bundle\SearchService;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $manager, BookRepository $bookRepository, ImageRepository $imageRepository, CategoryRepository $categoriesRepository): Response
    {
    
        /* This code is creating a query to retrieve the last 12 books with their associated image,
        author, and category information from the database. It joins the `Image`, `Book`, `Author`,
        and `Category` entities and filters the results to only include books where the `id` matches
        the `book` property of the `Image` entity. It also groups the results by the `id` of the
        `Image`, `slug` and `id` of the `Book`, `firstName` and `lastName` of the `Author`, `name`
        and `image` of the `Category`. Finally, it orders the results by the `publishedAt` property
        of the `Book` entity in descending order. The result is stored in the `lastBooks` variable. */
        $lastBooks = $manager->createQuery("SELECT i.id, i.url, i.name, b.slug, b.id, b.title, b.introduction, b.description, b.price, b.rating
                                            FROM App\Entity\Image i
                                            JOIN  App\Entity\Book b WITH i.id = b.id
                                        ")->setMaxResults(12)->getResult();
                                            
        
                        /**
                         * SELECT i.id, i.url, i.name, b.slug, b.id, b.title, b.introduction, b.description, b.price, u.firstName, u.lastName, c.name  as catName, c.image
                        * FROM App\Entity\Image i 
                        *JOIN i.book b
                        *JOIN b.authors u
                        *JOIN b.categories c
                        *WHERE b.id =  i.book
                        *GROUP BY i.id, b.slug, b.id, u.firstName, u.lastName, c.name, c.image
                        *ORDER BY b.publishedAt DESC
                         */
        /* This code is creating a query to retrieve the last 3 authors with their associated books,
        images, and category information from the database. It joins the `Author`, `Book`,
        `Category`, and `Image` entities and filters the results to only include authors where the
        `firstName` and `lastName` match the `firstName` and `lastName` properties of the `Author`
        entity. It also groups the results by the `id` of the `Author`, `firstName`, `lastName`,
        `description`, `id` and `title` of the `Book`, `introduction`, `publishedAt`, `name` and
        `url` of the `Image`, `name` and `image` of the `Category`. Finally, it orders the results
        by the `publishedAt` property of the `Book` entity in descending order. The result is stored
        in the `lastAuthors` variable. */
        $lastAuthors = $manager->createQuery('SELECT a.id, a.firstName, a.lastName, a.description, b.id, b.title, b.introduction, b.publishedAt, i.name, i.url, c.name as catName, c.image
                                                FROM App\Entity\Author a
                                                JOIN a.book b
                                                JOIN b.categories c
                                                JOIN b.images i
                                                WHERE a.firstName = a.firstName AND a.lastName = a.lastName
                                                GROUP BY a.id, a.firstName, a.lastName, a.description, b.id, b.title, b.introduction, b.publishedAt, i.name, i.url, c.name, c.image
                                                ORDER BY b.publishedAt DESC
                                        ')->setMaxResults(3)->getResult();


        return $this->render('home/index.html.twig', [
            'books' => $lastBooks,
            'authors' => $lastAuthors,
            'imageRepo' => $imageRepository->findByUrl([]),
            'categories' => $categoriesRepository->findAll(),
            'find' => $bookRepository->findByBookDate($request->query->get('publishedAt')),
            'bookAll' => $bookRepository->findAll()
            
        ]);
    }
}
