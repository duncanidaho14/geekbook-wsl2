<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\ImageRepository;
use App\Repository\OrderRepository;
use App\Repository\AuthorRepository;
use App\Repository\OrderDetailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(BookRepository $bookRepository, ImageRepository $imageRepository, AuthorRepository $authorRepository, OrderRepository $orderRepository, OrderDetailRepository $orderDetailRepository, EntityManagerInterface $manager): Response
    {
        $firstImageBook = $manager->createQuery('SELECT count(i) FROM App\Entity\Image i')->getSingleScalarResult();
        $countBook = $manager->createQuery('SELECT count(b) FROM App\Entity\Book b')->getSingleScalarResult();
        $jointure = $manager->createQuery("SELECT i.id, i.url, i.name FROM App\Entity\Image i JOIN i.book b")
                            ->getSQL();
        
        return $this->render('home/index.html.twig', [
            'books' => $bookRepository->findAll(),
            'authors' => $authorRepository->findAll(),
            'orders' => $orderRepository->findAll(),
            'orderDetails' => $orderDetailRepository->findAll(),
            'booksCreatedAt' => $bookRepository->findByBookDate(['now']),
            //'bookImage' => $imageRepository->findOneBy(['id' => $bookRepository->getId()]),
            'imageFirst' => $firstImageBook,
            'countBook' => $countBook,
            'jointure' => $jointure
        ]);
    }
}
