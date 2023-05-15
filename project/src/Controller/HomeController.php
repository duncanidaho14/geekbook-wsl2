<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $manager): Response
    {
    
        $lastBooks = $manager->createQuery("SELECT i.id, i.url, i.name, b.slug, b.id, b.title, b.introduction, b.description, u.firstName, u.lastName, c.name  as catName, c.image
                        FROM App\Entity\Image i 
                        JOIN i.book b
                        JOIN b.authors u
                        JOIN b.categories c
                        WHERE b.id =  i.book
                        GROUP BY i.id, b.slug, b.id, u.firstName, u.lastName, c.name, c.image
                        ORDER BY b.publishedAt DESC
                        ")->setMaxResults(12)->getResult();

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
            'authors' => $lastAuthors
        ]);
    }
}
