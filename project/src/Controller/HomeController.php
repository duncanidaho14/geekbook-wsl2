<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\OrderDetailRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(BookRepository $bookRepository, AuthorRepository $authorRepository, OrderRepository $orderRepository, OrderDetailRepository $orderDetailRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'books' => $bookRepository->findAll(),
            'authors' => $authorRepository->findAll(),
            'orders' => $orderRepository->findAll(),
            'orderDetails' => $orderDetailRepository->findAll(),
        ]);
    }
}
